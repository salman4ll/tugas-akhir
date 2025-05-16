<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
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
                $order->update([
                    'payment_status' => 'success',
                    'payment_method' => $request->payment_type,
                ]);
                break;
            case 'pending':
                $order->update([
                    'payment_status' => 'pending',
                    'payment_method' => $request->payment_type,
                ]);
                break;
            case 'deny':
                $order->update([
                    'payment_status' => 'failed',
                    'payment_method' => $request->payment_type,
                ]);
                break;
            case 'expire':
                $order->update([
                    'payment_status' => 'expired',
                    'payment_method' => $request->payment_type,
                ]);
                break;
            case 'cancel':
                $order->update([
                    'payment_status' => 'canceled',
                    'payment_method' => $request->payment_type,
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
