<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\CpCustomer;
use App\Models\Layanan;
use App\Models\Order;
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

        return view('product.payment_summary', compact('layanan', 'user'));
    }


    public function checkout(Request $request)
    {
        $user = Auth::user();
        $cpCustomer = $user->cpCustomer->first();
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
                $latitude = $request->isAddressUser ? $user->latitude : $request->latitude;
                $longitude = $request->isAddressUser ? $user->longitude : $request->longitude;
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

            $orderId = 'ORDER-' . uniqid();
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

            Order::create([
                'customer_id' => $user->id,
                'layanan_id' => $layananId,
                'perangkat_id' => $perangkat->id,
                'alamat_customer_id' => $request->isAddressUser ? null : ($address ? $address->id : null),
                'cp_customer_id' => $request->isCpUser ? $cpCustomer->id : ($createCpCustomer ? $createCpCustomer->id : null),
                'quantity' => 1,
                'order_date' => now(),
                'total_harga' => $totalPembayaran,
                'tanggal_pembayaran' => null,
                'riwayat_status_order_id' => 1,
                'unique_order' => $orderId,
                'snap_token' => $chargeResponse->token,
                'payment_status' => 0,
                'payment_url' => $chargeResponse->redirect_url,
                'sn_kit' => null,
                'sid' => null,
                'is_ttd' => 0,
                'jenis_pengiriman' => $jenisPengiriman,
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
        $orders = Order::with(['layanan', 'perangkat', 'alamatCustomer', 'cpCustomer'])
            ->where('customer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('product.order_history', compact('orders'));
    }

    public function getOrderDetail($orderId)
    {
        $decrypted_id = Crypt::decrypt($orderId);
        $order = Order::with(['layanan', 'perangkat', 'alamatCustomer', 'cpCustomer'])
            ->where('unique_order', $decrypted_id)
            ->firstOrFail();

        return view('product.order_detail', compact('order'));
    }
}
