@extends('layouts.blank-dashboard')

@section('title', 'Product')

@section('content')
    <div class="bg-gray-100">
        <div class="mx-auto container w-full px-8 py-10 min-h-screen text-gray-800">
            <form method="GET" action="{{ route('user.pesanan') }}" id="sortForm">
                <select name="sort" id="filter"
                    class="font-bold text-lg py-2 px-5 rounded-3xl border border-black hidden sm:flex"
                    onchange="document.getElementById('sortForm').submit()">
                    <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Pesanan Terbaru</option>
                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Pesanan Terlama</option>
                </select>
            </form>


            {{-- Navigasi --}}
            {{-- Navigasi untuk desktop --}}
            <div class="hidden sm:flex flex-row justify-around mt-5">
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
                    Dalam Perjalanan
                </a>
                <span>|</span>
                <a href="{{ route('user.pesanan', ['status' => 'siap_diambil', 'sort' => request('sort')]) }}"
                    class="{{ $statusFilter == 'siap_diambil' ? 'text-purple-600 font-semibold border-b-2 border-purple-600' : '' }}">
                    Siap Diambil
                </a>
                <span>|</span>
                <a href="{{ route('user.pesanan', ['status' => 'selesai', 'sort' => request('sort')]) }}"
                    class="{{ $statusFilter == 'selesai' ? 'text-purple-600 font-semibold border-b-2 border-purple-600' : '' }}">
                    Pesanan Selesai
                </a>
            </div>

            {{-- Navigasi untuk mobile --}}
            <div class="flex sm:hidden justify-between items-center mt-5">
                <form method="GET" action="{{ route('user.pesanan') }}" id="mobileFilterForm"
                    class="flex-grow mr-3 flex items-center">
                    {{-- Status --}}
                    <select name="status" id="statusSelectMobile"
                        class="flex-grow font-bold text-lg py-2 px-4 rounded-3xl border border-black"
                        onchange="document.getElementById('mobileFilterForm').submit()">
                        <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua</option>
                        <option value="belum_dibayar" {{ request('status') == 'belum_dibayar' ? 'selected' : '' }}>Belum
                            Dibayar</option>
                        <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Pesanan Diproses
                        </option>
                        <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dalam Perjalanan
                        </option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Pesanan Selesai
                        </option>
                    </select>

                    {{-- Sort (hidden input untuk simpan nilai sort) --}}
                    <input type="hidden" name="sort" id="sortInputMobile" value="{{ request('sort') ?? 'desc' }}" />
                </form>

                {{-- Tombol icon filter/sort --}}
                <button type="button" id="btnToggleSortMobile"
                    class="p-2 ml-3 rounded-full border border-gray-700 hover:bg-gray-200" aria-label="Toggle Sort">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 13.414V19a1 1 0 01-1.447.894l-4-2A1 1 0 019 17v-3.586L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                </button>
            </div>

            <div class="flex flex-col gap-10 mt-10">
                @if ($orders->count() > 0)
                    @foreach ($orders as $order)
                        <div class="w-full flex items-center justify-between">
                            <button onclick="window.location.href = '/user/pesanan/detail/{{ $order->unique_order }}'"
                                class="flex gap-5 items-center">
                                <img src="{{ asset('assets/images/' . $order->perangkat->produk->image) }}" alt=""
                                    class="w-[200px]">
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
                                    <button class="bg-blue-400 text-white px-10 py-3 rounded-lg"
                                        onclick="window.location.href = '{{ $order->payment_url }}'">Bayar
                                        Sekarang</button>
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

            <div class="mt-6 flex justify-between items-center">
                {{-- Tombol Sebelumnya --}}
                @if ($orders->onFirstPage())
                    <span class="px-4 py-2 text-gray-400 cursor-not-allowed">« Sebelumnya</span>
                @else
                    <a href="{{ $orders->previousPageUrl() }}"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded">« Sebelumnya</a>
                @endif

                {{-- Info Halaman --}}
                <span class="text-sm text-gray-600">
                    Halaman {{ $orders->currentPage() }} dari {{ $orders->lastPage() }}
                </span>

                {{-- Tombol Selanjutnya --}}
                @if ($orders->hasMorePages())
                    <a href="{{ $orders->nextPageUrl() }}"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded">Selanjutnya »</a>
                @else
                    <span class="px-4 py-2 text-gray-400 cursor-not-allowed">Selanjutnya »</span>
                @endif
            </div>

        </div>
    </div>

    <script>
        document.getElementById('btnToggleSortMobile').addEventListener('click', function() {
            const sortInput = document.getElementById('sortInputMobile');
            // Toggle sort value
            sortInput.value = (sortInput.value === 'desc') ? 'asc' : 'desc';
            // Submit form langsung
            document.getElementById('mobileFilterForm').submit();
        });
    </script>
@endsection
