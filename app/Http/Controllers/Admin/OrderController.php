<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MetodePengiriman;
use App\Models\Order;
use App\Models\RiwayatStatusOrder;
use App\Models\ShippingMethod;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request, $type = null)
    {
        $user = auth('admin')->user()->role->nama;

        // Status groups
        $statusGroups = [
            'belum_dibayar' => [1],
            'diproses' => [2, 3, 4],
            'dikirim' => [5, 6, 7, 10, 11],
            'selesai' => [8, 9],
        ];

        $query = Order::with([
            'customer',
            'customer.address',
            'layanan',
            'perangkat',
            'perangkat.produk',
            'riwayatStatusOrder.status',
            'alamatCustomer',
            'cpCustomer',
            'statusTerakhir'
        ]);

        // Filter jenis pengiriman (ambil_ditempat / ekspedisi)
        if ($type && $type !== 'all' && in_array($type, ['ambil_ditempat', 'ekspedisi'])) {
            $query->where('jenis_pengiriman', $type);
        }

        // Filter status group
        if ($request->has('status_group') && isset($statusGroups[$request->status_group])) {
            $statusIds = $statusGroups[$request->status_group];
            $query->whereHas('statusTerakhir', function ($q) use ($statusIds) {
                $q->whereIn('status_id', $statusIds);
            });
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('unique_order', 'like', "%{$search}%")
                    ->orWhere('total_harga', 'like', "%{$search}%")
                    ->orWhere('order_date', 'like', "%{$search}%")
                    ->orWhere('jenis_pengiriman', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('nama_perusahaan', 'like', "%{$search}%");
                    })
                    ->orWhereHas('statusTerakhir.status', function ($statusQuery) use ($search) {
                        $statusQuery->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        // Sorting functionality
        $sortByInput = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        $sortMap = [
            'created_at'        => 'created_at',
            'order_date'        => 'order_date',
            'total_harga'       => 'total_harga',
            'jenis_pengiriman'  => 'jenis_pengiriman',
            'unique_order'      => 'unique_order',
            'status'            => 'statusTerakhir.status.nama',
            'customer'          => 'customer.nama_perusahaan',
        ];

        $sortBy = $sortMap[$sortByInput] ?? 'created_at';

        if (array_key_exists($sortByInput, $sortMap)) {
            if ($sortByInput === 'customer') {
                $query->join('customers', 'orders.customer_id', '=', 'customers.id')
                    ->orderBy('customers.nama_perusahaan', $sortDirection)
                    ->select('orders.*');
            } elseif ($sortByInput === 'status') {
                $query->leftJoin('tbl_riwayat_status_order as rso', function ($join) {
                    $join->on('tbl_order.id', '=', 'rso.order_id')
                        ->whereRaw('rso.id IN (SELECT MAX(id) FROM tbl_riwayat_status_order GROUP BY order_id)');
                })
                    ->leftJoin('tbl_status as so', 'rso.status_id', '=', 'so.id')
                    ->orderBy('so.nama', $sortDirection)
                    ->select('tbl_order.*');
            } else {
                $query->orderBy($sortBy, $sortDirection);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $order = $query->paginate(5);

        // Clean up data before send to view
        $order->getCollection()->transform(function ($item) {
            unset(
                $item->id,
                $item->customer_id,
                $item->layanan_id,
                $item->perangkat_id,
                $item->alamat_customer_id,
                $item->cp_customer_id,
                $item->created_at,
                $item->updated_at
            );
            return $item;
        });

        // Append query string to pagination links
        $order->appends($request->query());

        return view('admin.pesanan.index', compact('order'));
    }



    public function updateStatus(Request $request, $id)
    {
        $order = Order::with(['statusTerakhir'])->where('unique_order', $id)->first();
        $status = $request->input('status');

        if (!$status || !in_array($status, ['new_order', 'process_order', 'packed_order', 'ready_for_pickup'])) {
            return redirect()->back()->withErrors(['status' => 'Status tidak valid']);
        }

        if ($order->statusTerakhir->status->nama === $status) {
            return redirect()->back()->with('info', 'Status pesanan sudah dalam keadaan ' . $status);
        }

        if ($status === 'new_order') {
            $riwayatStatus = RiwayatStatusOrder::create([
                'order_id' => $order->id,
                'status_id' => 3,
                'keterangan' => 'Pesanan sedang diproses',
            ]);
        } elseif ($status === 'process_order') {
            $riwayatStatus = RiwayatStatusOrder::create([
                'order_id' => $order->id,
                'status_id' => 4,
                'keterangan' => 'Pesanan s dikemas',
            ]);
        } else if ($status === 'packed_order') {
            if ($order->jenis_pengiriman === 'ambil_ditempat') {
                $riwayatStatus = RiwayatStatusOrder::create([
                    'order_id' => $order->id,
                    'status_id' => 10,
                    'keterangan' => 'Pesanan siap diambil',
                ]);
            } else {
                $riwayatStatus = RiwayatStatusOrder::create([
                    'order_id' => $order->id,
                    'status_id' => 5,
                    'keterangan' => 'Pesanan sudah siap dikirim',
                ]);
            }
        } elseif ($status === 'ready_for_pickup') {
            $riwayatStatus = RiwayatStatusOrder::create([
                'order_id' => $order->id,
                'status_id' => 11,
                'keterangan' => 'Pesanan sudah diambil',
            ]);
        } else {
            return redirect()->back()->withErrors(['status' => 'Transisi status tidak valid']);
        }

        $order->update([
            'riwayat_status_order_id' => $riwayatStatus->id,
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui');
    }

    public function canceledOrder(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string|max:255',
        ]);

        $order = Order::with(['statusTerakhir'])->where('unique_order', $id)->first();

        if (!$order) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan'
                ], 404);
            }
            return redirect()->back()->withErrors(['order' => 'Pesanan tidak ditemukan']);
        }

        if ($order->statusTerakhir->status->nama === 'canceled') {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan sudah dibatalkan'
                ], 400);
            }
            return redirect()->back()->with('info', 'Pesanan sudah dibatalkan');
        }

        $riwayatStatus = RiwayatStatusOrder::create([
            'order_id' => $order->id,
            'status_id' => 9,
            'keterangan' => $request->input('keterangan'),
        ]);

        $order->update([
            'riwayat_status_order_id' => $riwayatStatus->id,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibatalkan'
            ]);
        }

        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan');
    }


    public function getMetodePengiriman()
    {
        $shippingMethods = ShippingMethod::get()
            ->map(function ($method) {
                return [
                    'id' => $method->id,
                    'courier_code' => $method->courier_code,
                    'courier_service_code' => $method->courier_service_code,
                    'courier_name' => $method->courier_name,
                    'courier_service_name' => $method->courier_service_name,
                    'description' => $method->description,
                    'shipping_type' => $method->shipping_type,
                    'service_type' => $method->service_type,
                    'duration' => $method->duration,
                    'price' => $method->price,
                ];
            });

        return view('admin.ekspedisi.index', compact('shippingMethods'));
    }

    public function create()
    {
        $response = Http::withToken(env('BITESHIP_AUTH_TOKEN'))
            ->get('https://api.biteship.com/v1/couriers');

        $couriersRaw = $response->json('couriers');

        // Ambil kombinasi courier_code + courier_service_code yang sudah ada di DB
        $existingCombinations = MetodePengiriman::select('courier_code', 'courier_service_code')
            ->get()
            ->map(function ($item) {
                return $item->courier_code . '|' . $item->courier_service_code;
            })
            ->toArray();

        // Filter data API supaya hanya courier + service yang belum ada di DB
        $couriersFiltered = collect($couriersRaw)->filter(function ($courier) use ($existingCombinations) {
            $key = $courier['courier_code'] . '|' . $courier['courier_service_code'];
            return !in_array($key, $existingCombinations);
        });

        return view('admin.ekspedisi.create', ['couriers' => $couriersFiltered->groupBy('courier_code')]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'courier_code' => 'required',
            'courier_name' => 'required',
            'courier_service_code' => 'required',
            'courier_service_name' => 'required',
            'description' => 'nullable',
            'shipping_type' => 'required',
            'service_type' => 'required',
            'duration_estimate' => 'required',
            'is_active' => 'required|boolean',
        ]);

        // Simpan ke database (contoh tabel ekspedisis)
        MetodePengiriman::create($request->all());

        return redirect()->route('admin.get-metode-pengiriman')->with('success', 'Ekspedisi berhasil ditambahkan');
    }

    public function downloadLabelShipping($orderId)
    {
        try {
            $dataOrder = Order::with([
                'customer',
                'metodePengiriman',
                'perangkat.produk',
                'layanan',
                'alamatCustomer'
            ])->where('unique_order', $orderId)->first();

            if (!$dataOrder) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            $html = view('components.label_shipping', [
                'dataOrder' => $dataOrder
            ])->render();

            $pdf = Pdf::loadHTML($html)
                ->setPaper([0, 0, 400, 860], 'portrait')
                ->setOptions([
                    'dpi' => 96,
                    'defaultFont' => 'Arial',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'margin-top' => 0,
                    'margin-right' => 0,
                    'margin-bottom' => 0,
                    'margin-left' => 0,
                ]);

            return $pdf->download('label-shipping-' . $dataOrder->unique_order . '.pdf');
        } catch (\Exception $e) {
            Log::error('Error generating label shipping: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to generate label'], 500);
        }
    }
}
