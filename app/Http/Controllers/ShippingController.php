<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShippingController extends Controller
{
    public function getCourierList(Request $request)
    {
        $request->validate([
            'destination_latitude' => 'required|string',
            'destination_longitude' => 'required|string',
        ]);

        $response = Http::withToken(env('BITESHIP_AUTH_TOKEN'))
            ->baseUrl(env('BITESHIP_URL'))
            ->post('/v1/rates/couriers', [
                "origin_latitude" => "-6.542632391117262",
                "origin_longitude" => "106.77215270294894",
                "destination_latitude" => $request->destination_latitude,
                "destination_longitude" => $request->destination_longitude,
                "couriers" => "jne",
                "items" => [
                    [
                        "name" => "Polaris Coffee Cream 330ml isi 3 pcs",
                        "description" => "",
                        "length" => 10,
                        "width" => 10,
                        "height" => 0,
                        "weight" => 1000,
                        "value" => 285600,
                        "quantity" => 1
                    ]
                ]
            ]);

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json([
                'error' => 'Gagal mendapatkan data dari Biteship',
                'details' => $response->json(),
            ], $response->status());
        }
    }
}
