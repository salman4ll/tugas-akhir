@extends('layouts.blank-admin')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="p-6 space-y-6 h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin</h1>
                <p class="text-gray-600 mt-1">Selamat datang di panel admin. Berikut adalah ringkasan statistik pesanan.</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">{{ Carbon\Carbon::now()->format('l, d F Y') }}</p>
                <p class="text-sm text-gray-500">{{ Carbon\Carbon::now()->format('H:i') }} WIB</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Pesanan</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
                        <p class="text-sm font-medium mt-1 {{ $orderGrowth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $orderGrowth >= 0 ? '+' : '' }}{{ number_format($orderGrowth, 1) }}% dari bulan lalu
                        </p>
                    </div>
                    <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Pendapatan</p>
                        <p class="text-3xl font-bold text-gray-900">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        <p class="text-sm font-medium mt-1 {{ $revenueGrowth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $revenueGrowth >= 0 ? '+' : '' }}{{ number_format($revenueGrowth, 1) }}% dari bulan lalu
                        </p>
                    </div>
                    <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Customer</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalCustomers) }}</p>
                        <p class="text-sm text-gray-500 mt-1">Customer terdaftar</p>
                    </div>
                    <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pesanan Bulan Ini</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($ordersThisMonth) }}</p>
                        <p class="text-sm text-blue-600 font-medium mt-1">
                            Rp{{ number_format($revenueThisMonth, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="h-12 w-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pendapatan 6 Bulan Terakhir</h3>
                <canvas id="revenueChart" width="400" height="200"></canvas>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pesanan Harian Bulan Ini</h3>
                <canvas id="dailyOrdersChart" width="400" height="200"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pesanan Berdasarkan Status</h3>
                <div class="space-y-4">
                    @foreach ($ordersByStatus as $status => $count)
                        @php
                            $percentage = $totalOrders > 0 ? ($count / $totalOrders) * 100 : 0;
                            $statusClass = match ($status) {
                                'new_order' => 'bg-yellow-500',
                                'process_order' => 'bg-blue-500',
                                'packed_order' => 'bg-purple-500',
                                'pickup_order' => 'bg-green-500',
                                default => 'bg-gray-500',
                            };
                        @endphp
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 rounded-full {{ $statusClass }}"></div>
                                <span
                                    class="text-sm font-medium text-gray-700">{{ str_replace('_', ' ', ucfirst($status)) }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500">{{ number_format($count) }}</span>
                                <span class="text-xs text-gray-400">({{ number_format($percentage, 1) }}%)</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="{{ $statusClass }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pesanan Berdasarkan Jenis Pengiriman</h3>
                <div class="space-y-4">
                    @foreach ($ordersByShipping as $shipping => $count)
                        @php
                            $percentage = $totalOrders > 0 ? ($count / $totalOrders) * 100 : 0;
                            $shippingClass = $shipping === 'ekspedisi' ? 'bg-indigo-500' : 'bg-teal-500';
                        @endphp
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 rounded-full {{ $shippingClass }}"></div>
                                <span
                                    class="text-sm font-medium text-gray-700">{{ ucfirst(str_replace('_', ' ', $shipping)) }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500">{{ number_format($count) }}</span>
                                <span class="text-xs text-gray-400">({{ number_format($percentage, 1) }}%)</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="{{ $shippingClass }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Orders -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Pesanan Terbaru</h3>
                    <a href="{{ route('admin.orders', ['type' => 'all']) }}"
                        class="text-sm text-blue-600 hover:text-blue-700">Lihat Semua</a>
                </div>
                <div class="space-y-4">
                    @forelse($recentOrders as $order)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $order->customer->nama_perusahaan ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $order->unique_order }}</p>
                                <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">
                                    Rp{{ number_format($order->total_harga, 0, ',', '.') }}</p>
                                @php
                                    $status = $order->statusTerakhir->status->nama ?? 'Belum ada status';
                                    $statusClass = match ($status) {
                                        'new_order' => 'bg-yellow-100 text-yellow-800',
                                        'process_order' => 'bg-blue-100 text-blue-800',
                                        'packed_order' => 'bg-purple-100 text-purple-800',
                                        'pickup_order' => 'bg-green-100 text-green-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $statusClass }}">
                                    {{ str_replace('_', ' ', $status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Belum ada pesanan</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Customer</h3>
                <div class="space-y-4">
                    @forelse($topCustomers as $customer)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span
                                        class="text-sm font-medium text-blue-600">{{ substr($customer->nama_perusahaan, 0, 2) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $customer->nama_perusahaan }}</p>
                                    <p class="text-xs text-gray-500">{{ $customer->email }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ $customer->orders_count }} pesanan</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Belum ada data customer</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Monthly Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueData = @json($monthlyRevenue);

        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const revenueLabels = revenueData.map(item => monthNames[item.month - 1] + ' ' + item.year);
        const revenueValues = revenueData.map(item => item.total);

        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: revenueValues,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Pendapatan: Rp' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Daily Orders Chart
        const dailyCtx = document.getElementById('dailyOrdersChart').getContext('2d');
        const dailyData = @json($dailyOrdersThisMonth);

        const dailyLabels = dailyData.map(item => item.day);
        const dailyValues = dailyData.map(item => item.total);

        new Chart(dailyCtx, {
            type: 'bar',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Jumlah Pesanan',
                    data: dailyValues,
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>

@endsection
