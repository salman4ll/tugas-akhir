<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\CpCustomer;
use App\Models\Layanan;
use App\Models\Order;
use App\Models\RiwayatStatusOrder;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class OrderController extends Controller
{
    public function index($layanan_id)
    {
        $decrypted_id = Crypt::decrypt($layanan_id);
        $layanan = Layanan::with('perangkat')->findOrFail($decrypted_id);

        $layanan->encrypted_id = Crypt::encrypt($layanan->id);
        $layanan->formatted_price = formatIDR($layanan->harga_layanan);

        unset($layanan->id, $layanan->perangkat_id);

        $layanan->perangkat->encrypted_id = Crypt::encrypt($layanan->perangkat->id);
        $layanan->perangkat->formatted_price = formatIDR($layanan->perangkat->harga_perangkat);
        unset($layanan->perangkat->id, $layanan->perangkat->produk_id);

        $user = Auth::user();
        $address = $user->address->first();

        return view('product.payment_summary', compact('layanan', 'user', 'address'));
    }


    public function checkout(Request $request)
    {
        $user = Auth::user();
        $cpCustomer = $user->cpCustomer->first();
        $addressUser = $user->address->first();
        $data = $request->all();

        if ($request->isCpUser) {
            $data['nama'] = $cpCustomer->name;
            $data['email'] = $cpCustomer->email;
            $data['no_telp'] = $cpCustomer->no_telp;
        }

        $validator = Validator::make($data, [
            'layanan_id'     => 'required',
            'perangkat_id'   => 'required',
            'isCpUser'       => 'nullable|boolean',
            'isAddressUser'  => 'nullable|boolean',
            'ekspedisi'      => 'required|string',
            'nama'           => 'nullable|string|max:255',
            'email'          => 'nullable|email|max:255',
            'no_telp'          => 'nullable|string|max:15',
            'provinsi_id'    => 'nullable',
            'kabupaten_id'   => 'nullable',
            'kecamatan_id'   => 'nullable',
            'kelurahan_id'   => 'nullable',
            'kode_pos'       => 'nullable|string|max:10',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
            'detail'         => 'nullable|string|max:255',
        ]);

        $validator->sometimes(['nama', 'email', 'no_telp'], 'required', function ($input) {
            return $input->isCpUser == 0;
        });

        $validator->sometimes(
            ['provinsi_id', 'kabupaten_id', 'kecamatan_id', 'kelurahan_id', 'kode_pos', 'latitude', 'longitude', 'detail'],
            'required',
            function ($input) {
                return !$input->isAddressUser && $input->ekspedisi !== 'ambil_ditempat';
            }
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => 422,
                'message' => $validator->errors(),
            ], 422);
        }

        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $layananId = Crypt::decrypt($request->layanan_id);
        $layanan = Layanan::with('perangkat')->findOrFail($layananId);
        $perangkat = $layanan->perangkat;
        $hargaLayanan = $layanan->harga_layanan;
        $hargaPerangkat = $perangkat->harga_perangkat;
        $status_perusahan = $user->status_perusahaan;

        $shippingCost = 0;
        $ppn = 0;
        $pph = 0;
        $totalBiaya = 0;
        $totalPembayaran = 0;
        $jenisPengiriman = "";
        $address = null;
        $createCpCustomer = null;

        try {
            DB::beginTransaction();

            if ($request->ekspedisi == 'ambil_ditempat') {
                $shippingCost = 0;
                $jenisPengiriman = "ambil_ditempat";
            } else {
                $jenisPengiriman = "ekspedisi";
                $latitude = $request->isAddressUser ? $addressUser->latitude : $request->latitude;
                $longitude = $request->isAddressUser ? $addressUser->longitude : $request->longitude;
                $url = env('BITESHIP_URL') . '/v1/rates/couriers';
                $token = env('BITESHIP_AUTH_TOKEN');

                $response = Http::withHeader('Authorization', $token)
                    ->post($url, [
                        "origin_latitude" => "-6.54277410827191",
                        "origin_longitude" => "106.77194442401294",
                        "destination_latitude" => $latitude,
                        "destination_longitude" => $longitude,
                        "couriers" => "jne",
                        "items" => [
                            [
                                "name" => $perangkat->nama_perangkat,
                                "weight" => 4000,
                                "quantity" => 1,
                            ]
                        ]
                    ]);

                if ($response->failed()) {
                    throw new \Exception('Gagal mengambil data ongkir dari Biteship.');
                }


                $courier = ShippingMethod::find($request->ekspedisi);
                $pricings = $response['pricing'] ?? [];

                $matchedPricing = collect($pricings)->first(function ($item) use ($courier) {
                    return $item['courier_code'] === $courier->courier_code &&
                        $item['courier_service_code'] === $courier->courier_service_code;
                });

                if (!$matchedPricing) {
                    throw new \Exception('Ekspedisi tidak ditemukan dalam response Biteship.');
                }

                $shippingCost = $matchedPricing['price'];

                if (!$request->isAddressUser) {
                    $address = Address::create([
                        'customer_id' => $user->id,
                        'provinsi_id' => "$request->provinsi_id",
                        'kabupaten_id' => $request->kabupaten_id,
                        'kecamatan_id' => $request->kecamatan_id,
                        'kelurahan_id' => $request->kelurahan_id,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'rt' => $request->rt,
                        'rw' => $request->rw,
                        'alamat' => $request->detail,
                        'kode_pos' => $request->kode_pos,
                    ]);
                }
            }

            $totalBiaya = $hargaLayanan + $hargaPerangkat + $shippingCost;
            $ppn = $totalBiaya * 0.11;

            if ($status_perusahan == 1) {
                $pph = ($hargaLayanan + $shippingCost) * 0.02;
                $totalPembayaran = $totalBiaya - $pph;
                $ppn = 0;
            } elseif ($status_perusahan == 2) {
                $totalPembayaran = $totalBiaya + $ppn;
            } else {
                $pph = ($hargaLayanan + $shippingCost) * 0.02;
                $totalPembayaran = $totalBiaya - $pph + $ppn;
            }

            $orderId = 'ORDER-' . strtoupper(bin2hex(random_bytes(5)));
            $transactionData = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $totalPembayaran,
                ],
                'customer_details' => [
                    'first_name' => $request->isCpUser ? $cpCustomer->name : $request->nama,
                    'email' => $request->isCpUser ? $cpCustomer->email : $request->email,
                    'phone' => $request->isCpUser ? $cpCustomer->no_telp : $request->no_telp,
                ],
                'item_details' => [
                    [
                        'id' => $layananId,
                        'price' => $hargaLayanan,
                        'quantity' => 1,
                        'name' => $layanan->nama_layanan,
                    ],
                    [
                        'id' => $perangkat->id,
                        'price' => $hargaPerangkat,
                        'quantity' => 1,
                        'name' => $perangkat->nama_perangkat,
                    ],
                    [
                        'id' => 'shipping',
                        'price' => $shippingCost,
                        'quantity' => 1,
                        'name' => 'Shipping Cost',
                    ],
                    [
                        'id' => 'ppn',
                        'price' => $ppn,
                        'quantity' => 1,
                        'name' => 'PPN',
                    ],
                    [
                        'id' => 'pph',
                        'price' => -$pph,
                        'quantity' => 1,
                        'name' => 'PPH',
                    ],
                ],
            ];

            $chargeResponse = Snap::createTransaction($transactionData);

            if (!$request->isCpUser) {
                $createCpCustomer = CpCustomer::create([
                    'customer_id' => $user->id,
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'no_telp' => $request->no_telp,
                ]);
            }

            $order = Order::create([
                'customer_id' => $user->id,
                'layanan_id' => $layananId,
                'perangkat_id' => $perangkat->id,
                'alamat_customer_id' => $request->isAddressUser && $jenisPengiriman == 'ekspedisi' ? $addressUser->id : ($address ? $address->id : null),
                'cp_customer_id' => $request->isCpUser ? $cpCustomer->id : ($createCpCustomer ? $createCpCustomer->id : null),
                'quantity' => 1,
                'order_date' => now(),
                'total_harga' => $totalPembayaran,
                'tanggal_pembayaran' => null,
                'riwayat_status_order_id' => null,
                'unique_order' => $orderId,
                'snap_token' => $chargeResponse->token,
                'payment_status' => 'pending',
                'payment_url' => $chargeResponse->redirect_url,
                'sn_kit' => null,
                'sid' => null,
                'is_ttd' => 0,
                'jenis_pengiriman' => $jenisPengiriman,
                'metode_pengiriman_id' => $jenisPengiriman == 'ekspedisi' ? $request->ekspedisi : null,
                'biaya_pengiriman' => $shippingCost,
                'ppn' => $ppn,
                'pph' => $pph,
            ]);

            $riwayatStatusOrder = RiwayatStatusOrder::create([
                'order_id' => $order->id,
                'status_id' => 1,
                'keterangan' => 'Order created',
                'tanggal' => now(),
            ]);

            $order->update([
                'riwayat_status_order_id' => $riwayatStatusOrder->id,
            ]);

            DB::commit();

            return redirect($chargeResponse->redirect_url);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal melakukan checkout: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getOrder(Request $request)
    {
        $user = Auth::user();

        $sort = $request->input('sort', 'desc');
        $statusFilter = $request->input('status');

        $statusGroups = [
            'belum_dibayar' => [1],
            'diproses' => [2, 3, 4],
            'dikirim' => [5, 6, 7],
            'selesai' => [8, 9],
            'siap_diambil' => [10, 11],
        ];

        $query = Order::with([
            'layanan',
            'perangkat',
            'perangkat.produk',
            'statusTerakhir.status'
        ])->where('customer_id', $user->id);

        if ($statusFilter && isset($statusGroups[$statusFilter])) {
            $query->whereHas('statusTerakhir', function ($q) use ($statusGroups, $statusFilter) {
                $q->whereIn('status_id', $statusGroups[$statusFilter]);
            });
        }

        $query->orderBy('created_at', $sort);
        $orders = $query->paginate(10)->appends($request->query());

        return view('pesanan.index', compact('orders', 'sort', 'statusFilter'));
    }


    public function getOrderDetail($id)
    {
        $user = Auth::user();
        $status_perusahaan = $user->status_perusahaan;
        $order = Order::with([
            'layanan',
            'perangkat',
            'perangkat.produk',
            'riwayatStatusOrder',
            'riwayatStatusOrder.status',
            'statusTerakhir.status',
            'cpCustomer',
            'alamatCustomer',
            'alamatCustomer.province',
            'alamatCustomer.city',
            'alamatCustomer.district',
            'alamatCustomer.subdistrict',
            'metodePengiriman',
            'trackingOrder',
        ])->where('unique_order', $id)->first();
        // dd($order->metodePengiriman );

        $waktuPengirimanSampai = null;

        if ($order && $order->trackingOrder) {
            $completedTracking = $order->trackingOrder->firstWhere('status', 'completed');

            if ($completedTracking) {
                $waktuPengirimanSampai = $completedTracking->created_at;
            }
        }

        $statusId = $order->statusTerakhir->status->id;
        $activeStep = match (true) {
            ($statusId == 1 || $statusId == 2) => 1,
            (($statusId >= 3 && $statusId <= 6) || $statusId == 10) => 2,
            ($statusId == 7 || $statusId == 11) => 3,
            ($statusId == 8) => 4,
            ($statusId == 9) => 5,
            default => 0,
        };

        $alamatPengambilan = null;

        if ($order->jenis_pengiriman === 'ambil_ditempat' && $activeStep >= 2) {
            $alamatPengambilan = 'Perusahaan Jasa Telekomunikasi, Jl. Sholeh Iskandar No.KM 6, RT.04/RW.01, Cibadak, Kec. Tanah Sereal, Kota Bogor, Jawa Barat 16166';
        }

        $hargaPerangkat = $order->perangkat->harga_perangkat;
        $hargaLayanan = $order->layanan->harga_layanan;
        $shippingCost = $order->biaya_pengiriman;
        $totalBiaya = $order->hitungTotalBiaya();
        $ppn = $order->hitungPPN();
        $pph = $order->hitungPPH($status_perusahaan);
        $totalPembayaran = $totalBiaya + $ppn;
        $ringkasanTotalPembayaran = 0;

        if ($status_perusahaan == 1) {
            $pph = ($hargaLayanan + $shippingCost) * 0.02;
            $ringkasanTotalPembayaran = $totalBiaya - $pph;
        } elseif ($status_perusahaan == 2) {
            $ringkasanTotalPembayaran = $totalBiaya + $ppn;
        } else {
            $pph = ($hargaLayanan + $shippingCost) * 0.02;
            $ringkasanTotalPembayaran = $totalBiaya - $pph + $ppn;
        }


        return view('pesanan.detail', compact(
            'order',
            'status_perusahaan',
            'hargaPerangkat',
            'hargaLayanan',
            'shippingCost',
            'totalBiaya',
            'ppn',
            'pph',
            'totalPembayaran',
            'ringkasanTotalPembayaran',
            'waktuPengirimanSampai',
            'activeStep',
            'alamatPengambilan',
            'statusId'
        ));
    }
}
