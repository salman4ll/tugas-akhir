<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Perangkat;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
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
}
