<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MetodePengiriman;
use App\Models\Order;
use App\Models\RiwayatStatusOrder;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function index($type = null)
    {
        $user = auth('admin')->user()->role->nama;
        $query = Order::with(['customer', 'customer.address', 'layanan', 'perangkat', 'perangkat.produk', 'riwayatStatusOrder.status', 'alamatCustomer', 'cpCustomer', 'statusTerakhir'])
            ->orderBy('created_at', 'desc');

        if ($type && $type !== 'all' && in_array($type, ['ambil_ditempat', 'ekspedisi'])) {
            $query->where('jenis_pengiriman', $type);
        }

        $order = $query->paginate(5);

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

        // dd($order);

        return view('admin.pesanan.index', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::with(['statusTerakhir'])->where('unique_order', $id)->first();
        $status = $request->input('status');

        if (!$status || !in_array($status, ['new_order', 'process_order', 'packed_order'])) {
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
                'keterangan' => 'Pesanan sudah dikemas',
            ]);
        } else if ($status === 'packed_order') {
            $riwayatStatus = RiwayatStatusOrder::create([
                'order_id' => $order->id,
                'status_id' => 5,
                'keterangan' => 'Pesanan sudah siap dikirim',
            ]);
        } else {
            return redirect()->back()->withErrors(['status' => 'Transisi status tidak valid']);
        }

        $order->update([
            'riwayat_status_order_id' => $riwayatStatus->id,
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui');
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
}
