<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Orders
        $totalOrders = Order::count();

        // Total Revenue
        $totalRevenue = Order::sum('total_harga');

        // Total Customers
        $totalCustomers = User::count();

        // Orders This Month
        $ordersThisMonth = Order::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Revenue This Month
        $revenueThisMonth = Order::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_harga');

        // Orders by Status
        $ordersByStatus = Order::with('statusTerakhir.status')
            ->get()
            ->groupBy(function ($order) {
                return $order->statusTerakhir->status->nama ?? 'Belum ada status';
            })
            ->map->count();

        // Orders by Shipping Type
        $ordersByShipping = Order::select('jenis_pengiriman', DB::raw('count(*) as total'))
            ->groupBy('jenis_pengiriman')
            ->get()
            ->pluck('total', 'jenis_pengiriman');

        // Monthly Revenue Chart (Last 6 months)
        $monthlyRevenue = Order::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('SUM(total_harga) as total')
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Daily Orders This Month
        $dailyOrdersThisMonth = Order::select(
            DB::raw('DAY(created_at) as day'),
            DB::raw('COUNT(*) as total')
        )
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // Recent Orders
        $recentOrders = Order::with(['customer', 'statusTerakhir.status'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Top Customers by Order Count
        $topCustomers = User::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->limit(5)
            ->get();

        // Growth Percentage Calculations
        $lastMonthOrders = Order::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();

        $orderGrowth = $lastMonthOrders > 0 ?
            (($ordersThisMonth - $lastMonthOrders) / $lastMonthOrders) * 100 : 100;

        $lastMonthRevenue = Order::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('total_harga');

        $revenueGrowth = $lastMonthRevenue > 0 ?
            (($revenueThisMonth - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 100;

        return view('admin.index', compact(
            'totalOrders',
            'totalRevenue',
            'totalCustomers',
            'ordersThisMonth',
            'revenueThisMonth',
            'ordersByStatus',
            'ordersByShipping',
            'monthlyRevenue',
            'dailyOrdersThisMonth',
            'recentOrders',
            'topCustomers',
            'orderGrowth',
            'revenueGrowth'
        ));
    }
}
