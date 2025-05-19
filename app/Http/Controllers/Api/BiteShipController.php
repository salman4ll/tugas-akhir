<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Perangkat;
use App\Models\RiwayatStatusOrder;
use App\Models\ShippingMethod;
use App\Models\TrackingOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class BiteShipController extends Controller
{
    public function getCourier(Request $request)
    {
        $url = env('BITESHIP_URL') . '/v1/rates/couriers';
        $token = env('BITESHIP_AUTH_TOKEN');

        $validated = Validator::make($request->all(), [
            'destination_latitude' => 'required|numeric',
            'destination_longitude' => 'required|numeric',
            'device_id' => 'required|string',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => 422,
                'message' => $validated->errors(),
            ], 422);
        }

        $device_id = Crypt::decrypt($request->device_id);
        $perangkat = Perangkat::find($device_id);

        $response = Http::withHeader('Authorization', $token)
            ->post($url, [
                "origin_latitude" => "-6.54277410827191",
                "origin_longitude" => "106.77194442401294",
                "destination_latitude" => $request->destination_latitude,
                "destination_longitude" => $request->destination_longitude,
                "couriers" => "jne", // You can make this dynamic
                "items" => [
                    [
                        "name" => $perangkat->nama_perangkat,
                        "weight" => 4000,
                        "quantity" => 1,
                    ]
                ]
            ]);

        $responseData = $response->json();

        if (isset($responseData['success']) && !$responseData['success']) {
            return response()->json([
                'status' => 'error',
                'code' => $responseData['code'] ?? 500,
                'message' => $responseData['error'] ?? 'Unknown error',
            ], 400);
        }

        $pricing = collect($response->json('pricing'));

        $activeMethods = ShippingMethod::where('is_active', true)
            ->get()
            ->groupBy('courier_code')
            ->map(function ($items) {
                return $items->pluck('courier_service_code');
            });

        $filtered = $pricing->filter(function ($item) use ($activeMethods) {
            return $activeMethods->has($item['courier_code']) &&
                $activeMethods[$item['courier_code']]->contains($item['courier_service_code']);
        })->map(function ($item) use ($activeMethods) {
            $shippingMethod = ShippingMethod::where('courier_code', $item['courier_code'])
                ->where('courier_service_code', $item['courier_service_code'])
                ->first();

            return [
                'id' => $shippingMethod->id ?? null,
                'courier_code' => $item['courier_code'],
                'courier_service_code' => $item['courier_service_code'],
                'courier_name' => $item['courier_name'],
                'courier_service_name' => $item['courier_service_name'],
                'description' => $item['description'],
                'shipping_type' => $item['shipping_type'],
                'service_type' => $item['service_type'],
                'duration' => $item['duration'],
                'price' => $item['price'],
            ];
        })->values();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $filtered,
        ], 200);
    }

    public function createShipping(Request $request)
    {
        // Validasi request
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => 422,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = Auth::guard('sanctum')->user();

        $order = Order::with('metodePengiriman', 'perangkat', 'alamatCustomer', 'cpCustomer')
            ->where('unique_order', $request->order_id)
            ->where('customer_id', $user->id)
            ->first();

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Order tidak ditemukan atau tidak sesuai dengan user',
            ], 404);
        }

        if (!$order->cpCustomer || !$order->alamatCustomer || !$order->metodePengiriman || !$order->perangkat) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Data order tidak lengkap. Pastikan data customer, alamat, perangkat, dan metode pengiriman tersedia.',
            ], 400);
        }

        if ($order->payment_status !== 'success') {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Order belum dibayar',
            ], 400);
        }

        DB::beginTransaction();
        try {
            $url = env('BITESHIP_URL') . '/v1/orders';
            $token = env('BITESHIP_AUTH_TOKEN');

            $payload = [
                "origin_contact_name" => "PT MySatelite",
                "origin_contact_phone" => "081214931661",
                "origin_address" => "Jl. Sholeh Iskandar No.KM 6, RT.04/RW.01, Cibadak, Kec. Tanah Sereal, Kota Bogor, Jawa Barat 16166",
                "origin_postal_code" => 16166,
                "origin_coordinate" => [
                    "latitude" => -6.54277410827191,
                    "longitude" => 106.77194442401294
                ],
                "destination_contact_name" => $order->cpCustomer->nama,
                "destination_contact_phone" => $order->cpCustomer->no_telp,
                "destination_contact_email" => $order->cpCustomer->email,
                "destination_address" => $order->alamatCustomer->alamat,
                "destination_postal_code" => (int) $order->alamatCustomer->kode_pos,
                "destination_coordinate" => [
                    "latitude" => (float) $order->alamatCustomer->latitude,
                    "longitude" => (float) $order->alamatCustomer->longitude
                ],
                "courier_company" => $order->metodePengiriman->courier_code,
                "courier_type" => $order->metodePengiriman->courier_service_code,
                "delivery_type" => "now",
                "items" => [
                    [
                        "name" => $order->perangkat->nama_perangkat,
                        "weight" => 4000,
                        "quantity" => 1,
                        "value" => $order->total_harga,
                    ]
                ]
            ];

            $response = Http::withHeader('Authorization', $token)
                ->post($url, $payload);

            if (!$response->successful()) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'code' => $response->status(),
                    'message' => $response->json('message') ?? 'Gagal membuat pengiriman',
                    'error_detail' => $response->json(),
                ], $response->status());
            }

            $nomorResi = $response->json('courier.waybill_id');
            $trackingId = $response->json('courier.tracking_id');

            $riwayatStatusOrder = RiwayatStatusOrder::create([
                'order_id' => $order->id,
                'status_id' => 5,
                'keterangan' => 'Sedang Diambil',
                'tanggal' => now(),
            ]);

            $order->update([
                'reff_id_ship' => $response->json('id'),
                'nomor_resi' => $nomorResi,
                'tracking_id' => $trackingId,
                'riwayat_status_order_id' => $riwayatStatusOrder->id,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Berhasil membuat pengiriman',
                'data' => [
                    'nomor_resi' => $nomorResi,
                    'tracking_id' => $trackingId,
                ],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Terjadi kesalahan saat memproses pengiriman',
                'error_detail' => $e->getMessage(),
            ], 500);
        }
    }

    public function webhookBiteship(Request $request)
    {
        // Cek apakah request kosong atau tidak memiliki data yang dibutuhkan
        if (!$request->has(['status', 'order_id', 'courier_tracking_id'])) {
            return response()->json([
                'status' => 'ok',
                'code' => 200,
                'message' => 'Webhook installed successfully',
            ]);
        }

        // Jalankan logika utama hanya jika data tersedia
        $order = Order::where('reff_id_ship', $request->order_id)->first();

        if (!$order) {
            return response()->json([
                'status' => 'ok',
                'code' => 200,
                'message' => 'Order not found, but webhook accepted',
            ]);
        }

        $shipmentStatus = $this->handleStatusUpdate($request->status, $order);

        $latestNote = $this->getLatestTrackingNote(
            $request->courier_tracking_id
        );
        $trackingOrder = TrackingOrder::create([
            'order_id' => $order->id,
            'status' => $shipmentStatus,
            'note' => $latestNote,
        ]);

        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Webhook processed successfully',
            'data' => [
                'order_id' => $order->id,
                'shipment_status' => $shipmentStatus,
                'tracking_order_id' => $trackingOrder->id,
            ],
        ]);
    }


    private function handleStatusUpdate(string $status, Order $order): ?string
    {
        $map = [
            'allocated' => [
                'shipmentStatus' => 'allocated',
                'statusId' => 4,
                'keterangan' => 'Kurir Dialokasikan',
            ],
            'picking_up' => [
                'shipmentStatus' => 'process',
                'statusId' => 5,
                'keterangan' => 'Sedang Diambil',
            ],
            'picked' => [
                'shipmentStatus' => 'process',
                'statusId' => 6,
                'keterangan' => 'Sedang Dalam Perjalanan',
            ],
            'dropping_off' => [
                'shipmentStatus' => 'process',
                'statusId' => 6,
                'keterangan' => 'Sedang Dalam Perjalanan',
            ],
            'delivered' => [
                'shipmentStatus' => 'completed',
                'statusId' => 7,
                'keterangan' => 'Pengiriman Selesai',
            ],
        ];

        if (!array_key_exists($status, $map)) {
            abort(response()->json(['message' => 'Unsupported status'], 400));
        }

        $data = $map[$status];

        RiwayatStatusOrder::create([
            'order_id' => $order->id,
            'status_id' => $data['statusId'],
            'keterangan' => $data['keterangan'],
            'tanggal' => now(),
        ]);

        return $data['shipmentStatus'];
    }

    private function getLatestTrackingNote(string $courierTrackingId): ?string
    {
        $response = Http::withToken(env('BITESHIP_AUTH_TOKEN'))
            ->get(env('BITESHIP_URL') . '/v1/trackings/' . $courierTrackingId);

        $trackingData = $response->json();

        return collect($trackingData['history'] ?? [])
            ->sortByDesc('updated_at')
            ->first()['note'] ?? null;
    }
}
