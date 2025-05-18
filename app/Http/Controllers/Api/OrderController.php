<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RiwayatStatusOrder;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashedKey = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashedKey !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        $transactionStatus = $request->transaction_status;
        $orderId = $request->order_id;
        $order = Order::where('unique_order', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        switch ($transactionStatus) {
            case 'capture':
                if ($request->payment_type == 'credit_card') {
                    if ($request->fraud_status == 'challenge') {
                        $order->update(['payment_status' => 'pending']);
                    } else {
                        $order->update(['payment_status' => 'success']);
                    }
                }
                break;
            case 'settlement':

                $RiwayatStatusOrder =RiwayatStatusOrder::create([
                    'order_id' => $order->id,
                    'status_id' => 2,
                    'keterangan' => 'Pembayaran Berhasil',
                    'tanggal' => now(),
                ]);

                $order->update([
                    'payment_status' => 'success',
                    'payment_method' => $request->payment_type,
                    'riwayat_status_order_id' => $RiwayatStatusOrder->id,
                    'tanggal_pembayaran' => now(),
                ]);

                break;
            case 'pending':
                $order->update([
                    'payment_status' => 'pending',
                    'payment_method' => $request->payment_type,
                ]);
                break;
            case 'deny':
                $RiwayatStatusOrder = RiwayatStatusOrder::create([
                    'order_id' => $order->id,
                    'status_id' => 9,
                    'keterangan' => 'Pembayaran Gagal',
                    'tanggal' => now(),
                ]);

                $order->update([
                    'payment_status' => 'failed',
                    'payment_method' => $request->payment_type,
                    'riwayat_status_order_id' => $RiwayatStatusOrder->id,
                ]);

                break;
            case 'expire':
                $RiwayatStatusOrder = RiwayatStatusOrder::create([
                    'order_id' => $order->id,
                    'status_id' => 9,
                    'keterangan' => 'Pembayaran Kadaluarsa',
                    'tanggal' => now(),
                ]);

                $order->update([
                    'payment_status' => 'expired',
                    'payment_method' => $request->payment_type,
                    'riwayat_status_order_id' => $RiwayatStatusOrder->id,
                ]);

                break;
            case 'cancel':
                $RiwayatStatusOrder = RiwayatStatusOrder::create([
                    'order_id' => $order->id,
                    'status_id' => 9,
                    'keterangan' => 'Pembayaran Dibatalkan',
                    'tanggal' => now(),
                ]);

                $order->update([
                    'payment_status' => 'canceled',
                    'payment_method' => $request->payment_type,
                    'riwayat_status_order_id' => $RiwayatStatusOrder->id,
                ]);

                break;
            default:
                $order->update([
                    'payment_status' => 'unknown',
                    'payment_method' => $request->payment_type,
                ]);
                break;
        }

        return response()->json(['message' => 'Callback received successfully']);
    }
}
