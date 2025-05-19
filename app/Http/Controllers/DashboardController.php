<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orderIsNotPaid = Order::with(['riwayatStatusOrder', 'riwayatStatusOrder.status', 'perangkat', 'layanan', 'alamatCustomer', 'alamatCustomer.province', 'alamatCustomer.city', 'alamatCustomer.district', 'alamatCustomer.subDistrict'])
            ->where('customer_id', $user->id)
            ->where('payment_status', '=', 'pending')
            ->latest('created_at')
            ->first();

        $order = Order::with(['statusTerakhir', 'perangkat', 'layanan'])
            ->where('customer_id', $user->id)
            ->latest('created_at')
            ->take(5) // atau ->limit(5)
            ->get();


        return view('dashboard.index', compact('user', 'orderIsNotPaid', 'order'));
    }
}
