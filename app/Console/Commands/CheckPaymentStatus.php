<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\RiwayatStatusOrder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckPaymentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-payment-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info("Starting payment status check at " . now());
        $orders = Order::where('payment_status', 'pending')->get();

        foreach ($orders as $order) {
            Log::info("Checking payment status for order: {$order->unique_order}");
            $orderId = $order->unique_order;

            $url = "https://api.sandbox.midtrans.com/v2/{$orderId}/status";

            $serverKey = env('MIDTRANS_SERVER_KEY');
            $authHeader = base64_encode($serverKey . ':');

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . $authHeader,
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();
                Log::info("Response for order {$orderId}: " . json_encode($data));

                // Handle jika transaksi tidak ditemukan (404)
                if (
                    isset($data['status_code']) && $data['status_code'] === '404' &&
                    isset($data['status_message']) && $data['status_message'] === "Transaction doesn't exist."
                ) {
                    if ($order->created_at->addDay()->gt(now())) {
                        Log::info("Order {$orderId} is still within 1 day of creation, skipping expiration check.");
                        continue;
                    }
                    RiwayatStatusOrder::create([
                        'order_id' => $order->id,
                        'status_id' => 9,
                        'keterangan' => 'Pembayaran Kadaluarsa (Transaction tidak ditemukan)',
                        'tanggal' => now(),
                    ]);

                    $order->update([
                        'payment_status' => 'expired',
                    ]);

                    $this->info("Order {$orderId} updated to expired because transaction doesn't exist");
                    continue;
                }

                // Ambil data penting dari response
                $transactionStatus = $data['transaction_status'] ?? null;
                $paymentType = $data['payment_type'] ?? null;
                $fraudStatus = $data['fraud_status'] ?? null;

                if (!$transactionStatus) {
                    $this->error("No transaction_status for order {$orderId}");
                    continue;
                }

                switch ($transactionStatus) {
                    case 'capture':
                        if ($paymentType == 'credit_card') {
                            if ($fraudStatus == 'challenge') {
                                $order->update(['payment_status' => 'pending', 'payment_method' => $paymentType]);
                            } else {
                                $order->update(['payment_status' => 'success', 'payment_method' => $paymentType]);
                            }
                        }
                        break;

                    case 'settlement':
                        $riwayat = RiwayatStatusOrder::create([
                            'order_id' => $order->id,
                            'status_id' => 2,
                            'keterangan' => 'Pembayaran Berhasil',
                            'tanggal' => now(),
                        ]);
                        $order->update([
                            'payment_status' => 'success',
                            'payment_method' => $paymentType,
                            'riwayat_status_order_id' => $riwayat->id,
                            'tanggal_pembayaran' => now(),
                        ]);
                        break;

                    case 'pending':
                        $order->update([
                            'payment_status' => 'pending',
                            'payment_method' => $paymentType,
                        ]);
                        break;

                    case 'deny':
                        $riwayat = RiwayatStatusOrder::create([
                            'order_id' => $order->id,
                            'status_id' => 9,
                            'keterangan' => 'Pembayaran Gagal',
                            'tanggal' => now(),
                        ]);
                        $order->update([
                            'payment_status' => 'failed',
                            'payment_method' => $paymentType,
                            'riwayat_status_order_id' => $riwayat->id,
                        ]);
                        break;

                    case 'expire':
                        $riwayat = RiwayatStatusOrder::create([
                            'order_id' => $order->id,
                            'status_id' => 9,
                            'keterangan' => 'Pembayaran Kadaluarsa',
                            'tanggal' => now(),
                        ]);
                        $order->update([
                            'payment_status' => 'expired',
                            'payment_method' => $paymentType,
                            'riwayat_status_order_id' => $riwayat->id,
                        ]);
                        break;

                    case 'cancel':
                        $riwayat = RiwayatStatusOrder::create([
                            'order_id' => $order->id,
                            'status_id' => 9,
                            'keterangan' => 'Pembayaran Dibatalkan',
                            'tanggal' => now(),
                        ]);
                        $order->update([
                            'payment_status' => 'canceled',
                            'payment_method' => $paymentType,
                            'riwayat_status_order_id' => $riwayat->id,
                        ]);
                        break;

                    default:
                        $order->update([
                            'payment_status' => 'unknown',
                            'payment_method' => $paymentType,
                        ]);
                        break;
                }

                $this->info("Order {$orderId} status processed: {$transactionStatus}");
            } else {
                $this->error("Failed to fetch status for order {$orderId}");
            }
        }

        return 0;
    }
}
