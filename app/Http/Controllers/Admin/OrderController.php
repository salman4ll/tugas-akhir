<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RiwayatStatusOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
