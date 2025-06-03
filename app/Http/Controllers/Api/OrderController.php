<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RiwayatStatusOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

                $RiwayatStatusOrder = RiwayatStatusOrder::create([
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

    public function confirmationOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'image' => 'required|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $order = Order::with('statusTerakhir')->where('unique_order', $request->order_id)->firstOrFail();

            if ($order->statusTerakhir->status_id != 7) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pesanan tidak dalam status yang dapat dikonfirmasi.',
                ], 422);
            }

            $path = $request->file('image')->store('order-confirmation', 'public');

            $RiwayatStatusOrder = RiwayatStatusOrder::create([
                'order_id' => $order->id,
                'status_id' => 8,
                'keterangan' => 'Pesanan telah dikonfirmasi',
                'tanggal' => now(),
            ]);

            $order->confirmation_image = $path;
            $order->riwayat_status_order_id = $RiwayatStatusOrder->id;
            $order->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pesanan berhasil dikonfirmasi',
                'data' => [
                    'order_id' => $order->id,
                    'confirmation_image_url' => Storage::url($path),
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat konfirmasi pesanan.',
                'error_detail' => $e->getMessage(),
            ], 500);
        }
    }

    public function getOrderDetail($id)
    {
        $order = Order::with(['customer', 'layanan', 'perangkat', 'alamatCustomer', 'cpCustomer', 'riwayatStatusOrder.status'])
            ->where('unique_order', $id)
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $statusMap = [
            1 => 'Wajib Pungut (WAPU)',
            2 => 'Non-Wajib Pungut (non-WAPU) tanpa potong PPH 23',
            3 => 'Non-Wajib Pungut (non-WAPU) potong PPH 23',
        ];

        $pengirimanMap = [
            'ekspedisi' => 'Ekspedisi',
            'ambil_ditempat' => 'Ambil Di Tempat',
        ];


        $address = $order->customer->address->first();

        $customer = [
            'nama' => $order->customer->nama_perusahaan,
            'username' => $order->customer->username,
            'npwp' => $order->customer->npwp_perusahaan,
            'email' => $order->customer->email_perusahaan,
            'no_telp' => $order->customer->no_telp_perusahaan,
            'status_perusahaan' => $statusMap[$order->customer->status_perusahaan] ?? 'Status tidak diketahui',
            'alamat' => [
                'provinsi' => $address?->province?->nama ?? '-',
                'kabupaten' => $address?->city?->nama ?? '-',
                'kecamatan' => $address?->district?->nama ?? '-',
                'kelurahan' => $address?->subDistrict?->nama ?? '-',
                'alamat_lengkap' => $address?->alamat ?? '-',
            ],
        ];

        $order = [
            'id' => $order->unique_order,
            'layanan' => $order->layanan->nama_layanan ?? '-',
            'perangkat' => $order->perangkat->nama_perangkat ?? '-',
            'produk' => $order->perangkat->produk?->nama_produk ?? '-',
            'cp_customer' => $order->cpCustomer?->nama ?? '-',
            'harga' => formatIDR($order->total_harga ?? 0),
            'jenis_pengiriman' => $pengirimanMap[$order->jenis_pengiriman] ?? '-',
            'status_terakhir' => $order->statusTerakhir?->status?->nama ?? '-',
            'tanggal_pemesanan' => formatTanggal($order->created_at) ?? '-',
            'penerima' => $order->cpCustomer?->nama ?? '-',
            'no_telp_penerima' => $order->cpCustomer?->no_telp ?? '-',
            'alamat_pengiriman' => [
                'provinsi' => $order->alamatCustomer?->province?->nama ?? '-',
                'kabupaten' => $order->alamatCustomer?->city?->nama ?? '-',
                'kecamatan' => $order->alamatCustomer?->district?->nama ?? '-',
                'kelurahan' => $order->alamatCustomer?->subDistrict?->nama ?? '-',
                'alamat_lengkap' => $order->alamatCustomer?->alamat ?? '-',
            ],
        ];

        // dd($customer);

        return response()->json([
            'success' => true,
            'data' => [
                'order' => $order,
                'customer' => $customer,
            ],
        ]);
    }
}
