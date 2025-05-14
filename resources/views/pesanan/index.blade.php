@extends('layouts.blank')

@section('title', 'Product')

@section('content')
    <div class="bg-gray-100">
        <div class="mx-auto max-w-7xl px-8 py-10 min-h-screen text-gray-800">
            <div class="flex flex-row justify-between items-center">
                <h2 class="font-bold text-lg mb-4">Pesanan</h2>
                {{-- Filter --}}
                <select name="filter" id="filter" class="font-bold text-lg mb-4 py-2 px-5 rounded-3xl border border-2 border-black">
                    <option value="terbaru" selected>Pesanan Terbaru</option>
                    <option value="terbaru" selected>Pesanan Terbaru</option>
                    <option value="terbaru" selected>Pesanan Terbaru</option>
                </select>
            </div>

            {{-- Navigasi --}}
            <div class="flex flex-row justify-around mt-5">
                <button class="border-b-2 border-b-purple-600 px-5 text-purple-600 font-semibold">Semua</button>
                <span>|</span>
                <button>Mengajukan Pengiriman</button>
                <span>|</span>
                <button>Belum Dibayar</button>
                <span>|</span>
                <button>Pesanan Diproses</button>
                <span>|</span>
                <button>Pesanan Selesai</button>
            </div>

            {{-- Products --}}
            <div class="flex flex-col gap-10 mt-10">
                {{-- Item --}}
                <div class="w-full flex items-center justify-between">
                    <button onclick="goToDetailOrder()" class="flex gap-5 items-center">
                        <img src="/assets/images/produk-link.png" alt="" class="w-[200px]">
                        <div class="flex flex-col gap-2 text-left">
                            <p class="font-bold text-2xl">Mobility Site</p>
                            <p class="flex gap-3 text-md items-center text-purple-800"><span>o</span>Mengajukan Pengiriman</p>
                            <p class="text-sm text-gray-600">Pesanan dibuat tanggal 20 Maret 2024</p>
                            <p class="text-sm text-gray-600">ID Pesanan: D0195823500</p>
                        </div>
                    </button>

                    <div class="flex flex-col gap-3 items-center w-[17%]">
                        <p class="font-bold text-3xl">IDR0</p>
                        <button class="bg-blue-400 opacity-40 cursor-not-allowed text-white px-10 py-3 rounded-lg">Bayar Sekarang</butto>
                    </div>
                </div>

                {{-- Item --}}
                <div class="w-full flex items-center justify-between">
                    <button onclick="goToDetailOrder()" class="flex gap-5 items-center">
                        <img src="/assets/images/produk-link.png" alt="" class="w-[200px]">
                        <div class="flex flex-col gap-2 text-left">
                            <p class="font-bold text-2xl">Mobility Site</p>
                            <p class="flex gap-3 text-md items-center text-orange-600"><span>o</span>Proses Pembayaran</p>
                            <p class="text-sm text-gray-600">Pesanan dibuat tanggal 20 Maret 2024</p>
                            <p class="text-sm text-gray-600">ID Pesanan: D0195823500</p>
                            <p class="text-sm bg-purple-600 rounded-xl text-white px-3">Pesan otomatis batal pada tanggal 1 April 2024</p>
                        </div>
                    </button>

                    <div class="flex flex-col gap-3 items-center w-[17%]">
                        <p class="font-bold text-3xl">IDR86,000,000</p>
                        <button class="bg-blue-400 text-white px-10 py-3 rounded-lg">Bayar Sekarang</butto>
                    </div>
                </div>

                {{-- Item --}}
                <div class="w-full flex items-center justify-between">
                    <button onclick="goToDetailOrder()" class="flex gap-5 items-center">
                        <img src="/assets/images/produk-link.png" alt="" class="w-[200px]">
                        <div class="flex flex-col gap-2">
                            <p class="font-bold text-2xl text-left">Mobility Site</p>
                            <p class="flex gap-3 text-md items-center text-purple-800"><span>o</span>Mengajukan Pengiriman</p>
                            <p class="text-sm text-gray-600">Pesanan dibuat tanggal 20 Maret 2024</p>
                            <p class="text-sm text-gray-600">ID Pesanan: D0195823500</p>
                        </div>
                    </button>

                    <div class="flex flex-col gap-3 items-center w-[17%]">
                        <p class="font-bold text-3xl">IDR0</p>
                        <button class="bg-blue-400 text-white px-10 py-3 rounded-lg">Bayar Sekarang</butto>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function goToDetailOrder(){
            window.location.href = '/user/pesanan/detail';
        }
    </script>
@endsection