@extends('layouts.blank')

@section('title', 'Product')

@section('content')
<div class="bg-gray-100">
    <div class="mx-auto max-w-7xl px-8 py-10 min-h-screen text-gray-800">
        <div class="grid grid-cols-12 gap-8">
            {{-- Sidebar Kategori --}}
            <div class="col-span-2">
                <h2 class="font-bold text-lg mb-4">Kategori</h2>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="block px-4 py-2 rounded-md hover:bg-gray-100 text-gray-700">Semua</a>
                    </li>
                    <li>
                        <a href="#" class="block px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded-md">SBS</a>
                    </li>
                </ul>
            </div>
    
            {{-- Konten Produk --}}
            <div class="col-span-10">
                {{-- Search Bar --}}
                <div class="relative mb-6">
                    <input type="text" placeholder="Cari produk mu disini"
                        class="w-full border border-gray-300 rounded-md py-2 px-4 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <span class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                        </svg>
                    </span>
                </div>
    
                {{-- Header Produk --}}
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Produk (2)</h3>
                    <a href="#" class="text-blue-600 hover:underline">Bandingkan Produk &rarr;</a>
                </div>
    
                {{-- Produk List --}}
                <div class="space-y-6">
                    {{-- Produk 1 --}}
                    <div class="flex items-center justify-between border border-gray-200 rounded-md p-4">
                        <div class="flex items-start space-x-4">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Starlink_Satellite_Dish.jpeg/640px-Starlink_Satellite_Dish.jpeg" alt="Fixed Site"
                                class="w-16 h-16 object-contain">
                            <div>
                                <h4 class="text-lg font-bold">Fixed Site</h4>
                                <p class="text-sm font-semibold">Key Feature:</p>
                                <p class="text-sm text-gray-600">Unlimited Standard Data, Public IP, Network Priority, Priority Support</p>
                            </div>
                        </div>
                        <button onclick="window.location.href='/detail_product'" class="bg-[#ED4436] text-white px-4 py-2 rounded-md hover:bg-[#fe7164]">Beli Sekarang</button>
                    </div>
    
                    {{-- Produk 2 --}}
                    <div class="flex items-center justify-between border border-gray-200 rounded-md p-4">
                        <div class="flex items-start space-x-4">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Starlink_Satellite_Dish.jpeg/640px-Starlink_Satellite_Dish.jpeg" alt="Land Mobility"
                                class="w-16 h-16 object-contain">
                            <div>
                                <h4 class="text-lg font-bold">Land Mobility</h4>
                                <p class="text-sm font-semibold">Key Feature:</p>
                                <p class="text-sm text-gray-600">Unlimited Inland Data, In-Motion + Ocean Use, Network Priority, Priority Support</p>
                            </div>
                        </div>
                        <button class="bg-[#ED4436] text-white px-4 py-2 rounded-md hover:bg-[#fe7164]">Beli Sekarang</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
