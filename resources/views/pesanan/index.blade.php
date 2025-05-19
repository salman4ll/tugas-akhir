@extends('layouts.blank-dashboard')

@section('title', 'Product')

@section('content')
    <div class="bg-gray-100">
        <div class="mx-auto max-w-7xl px-8 py-10 min-h-screen text-gray-800">
            <form method="GET" action="{{ route('user.pesanan') }}" id="sortForm">
                <select name="sort" id="filter" class="font-bold text-lg py-2 px-5 rounded-3xl border border-black"
                    onchange="document.getElementById('sortForm').submit()">
                    <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Pesanan Terbaru</option>
                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Pesanan Terlama</option>
                </select>
            </form>


            {{-- Navigasi --}}
            <div class="flex flex-row justify-around mt-5">
                @php
                    $statusFilter = request('status');
                @endphp
                <a href="{{ route('user.pesanan', ['sort' => request('sort')]) }}"
                    class="{{ $statusFilter == '' ? 'text-purple-600 font-semibold border-b-2 border-purple-600' : '' }}">
                    Semua
                </a>
                <span>|</span>
                <a href="{{ route('user.pesanan', ['status' => 'belum_dibayar', 'sort' => request('sort')]) }}"
                    class="{{ $statusFilter == 'belum_dibayar' ? 'text-purple-600 font-semibold border-b-2 border-purple-600' : '' }}">
                    Belum Dibayar
                </a>
                <span>|</span>
                <a href="{{ route('user.pesanan', ['status' => 'diproses', 'sort' => request('sort')]) }}"
                    class="{{ $statusFilter == 'diproses' ? 'text-purple-600 font-semibold border-b-2 border-purple-600' : '' }}">
                    Pesanan Diproses
                </a>
                <span>|</span>
                <a href="{{ route('user.pesanan', ['status' => 'dikirim', 'sort' => request('sort')]) }}"
                    class="{{ $statusFilter == 'dikirim' ? 'text-purple-600 font-semibold border-b-2 border-purple-600' : '' }}">
                    Pesanan Dikirim
                </a>
                <span>|</span>
                <a href="{{ route('user.pesanan', ['status' => 'selesai', 'sort' => request('sort')]) }}"
                    class="{{ $statusFilter == 'selesai' ? 'text-purple-600 font-semibold border-b-2 border-purple-600' : '' }}">
                    Pesanan Selesai
                </a>
            </div>


            <div class="flex flex-col gap-10 mt-10">
                @if ($orders->count() > 0)
                    @foreach ($orders as $order)
                        <div class="w-full flex items-center justify-between">
                            <button onclick="window.location.href = '/user/pesanan/detail/{{ $order->unique_order }}'"
                                class="flex gap-5 items-center">
                                <img src="{{ asset('assets/images/' . $order->perangkat->produk->image) }}" alt="" class="w-[200px]">
                                <div class="flex flex-col gap-2 text-left">
                                    <p class="font-bold text-2xl">
                                        {{ $order->perangkat->produk->nama_produk ?? 'Tanpa Layanan' }}</p>

                                    <p class="flex gap-3 text-md items-center text-purple-800">
                                        <span>o</span>{{ $order->statusTerakhir?->status?->nama ?? 'Status Tidak Diketahui' }}
                                    </p>

                                    <p class="text-sm text-gray-600">Pesanan dibuat tanggal
                                        {{ $order->created_at->translatedFormat('d F Y') }}</p>
                                    <p class="text-sm text-gray-600">ID Pesanan: {{ $order->unique_order }}</p>
                                </div>
                            </button>

                            <div class="flex flex-col gap-3 items-center w-[17%]">
                                <p class="font-bold text-3xl">IDR{{ number_format($order->total_harga, 0, ',', '.') }}</p>
                                @if ($order->statusTerakhir?->status?->id == 1)
                                    <button class="bg-blue-400 text-white px-10 py-3 rounded-lg" onclick="window.location.href = '{{ $order->payment_url }}'">Bayar Sekarang</button>
                                @else
                                    <button
                                        class="bg-blue-400 opacity-40 cursor-not-allowed text-white px-10 py-3 rounded-lg">Bayar
                                        Sekarang</button>
                                @endif

                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-gray-500 py-20">
                        <p class="text-xl font-semibold">Data pesanan tidak ditemukan.</p>
                        <p>Silakan coba dengan filter atau kata kunci lain.</p>
                    </div>
                @endif
            </div>


            <div class="mt-10">
                {{ $orders->links() }}
            </div>

        </div>
    </div>
@endsection
