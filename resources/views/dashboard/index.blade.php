@extends('layouts.blank-dashboard')

@section('title', 'Product')

@section('content')
    <div class="bg-gray-100">
        <div class="container grid grid-cols-12 w-full mx-auto py-10 gap-8">
            @if ($orderIsNotPaid)
                <div class="col-span-4 bg-gray-200 p-4 rounded-lg flex flex-col gap-4">
                    <div class="flex justify-between">
                        <div class="flex flex-col">
                            <span>Pesanan Belum Dibayar</span>
                            <span class="text-xl lg:text-2xl font-bold">{{ formatIDR($orderIsNotPaid->total_harga) }}</span>
                        </div>
                        <button class="text-white w-fit px-4 py-2 rounded-lg bg-purple-600 hover:bg-purple-700 transition"
                            onclick="window.location.href = '{{ $orderIsNotPaid->payment_url }}'">Bayar Sekarang</button>
                    </div>
                    <div class="flex flex-col gap-2">
                        <span class="text-gray-400">Untuk Pembayaran</span>
                        <div class="flex gap-12">
                            <div class="flex flex-col gap-2">
                                <span class="text-gray-400">ID Order</span>
                                <span class="text-gray-400">Perangkat</span>
                                <span class="text-gray-400">Layanan</span>
                                <span class="text-gray-400">Alamat</span>
                            </div>
                            <div class="flex flex-col gap-2">
                                <span class="font-bold">{{ $orderIsNotPaid->unique_order }}</span>
                                <span class="font-bold">{{ $orderIsNotPaid->perangkat->nama_perangkat }}</span>
                                <span class="font-bold">{{ $orderIsNotPaid->layanan->nama_layanan }}</span>
                                <span class="font-bold">
                                    {{ $orderIsNotPaid->alamatCustomer->alamat }}, RT
                                    {{ $orderIsNotPaid->alamatCustomer->rt }}, RW
                                    {{ $orderIsNotPaid->alamatCustomer->rw }},
                                    Desa {{ $orderIsNotPaid->alamatCustomer->subDistrict->nama }},
                                    Kecamatan {{ $orderIsNotPaid->alamatCustomer->district->nama }},
                                    Kota {{ $orderIsNotPaid->alamatCustomer->city->nama }},
                                    {{ $orderIsNotPaid->alamatCustomer->province->nama }},
                                    {{ $orderIsNotPaid->alamatCustomer->kode_pos }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-span-4 bg-gray-200 p-4 rounded-lg text-center">
                    <p class="text-gray-600">Tidak ada pesanan yang perlu dibayar saat ini.</p>
                </div>
            @endif
            <div class="col-span-8 bg-gray-200 p-4 rounded-lg flex flex-col gap-4">
                <span class="font-bold text-xl">Pesanan</span>

                @if ($order->isEmpty())
                    <p class="text-center text-gray-500 py-6">Belum ada pesanan.</p>
                @else
                    <table class="min-w-full border border-gray-300 rounded-lg overflow-hidden">
                        <thead class="">
                            <tr>
                                <th class="py-2 px-4 border-b border-gray-400 text-left">ID Order</th>
                                <th class="py-2 px-4 border-b border-gray-400 text-left">Nama Produk</th>
                                <th class="py-2 px-4 border-b border-gray-400 text-left">Tanggal Pesanan</th>
                                <th class="py-2 px-4 border-b border-gray-400 text-left">Status Terakhir</th>
                                <th class="py-2 px-4 border-b border-gray-400 text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order as $item)
                                <tr class="hover:bg-gray-100">
                                    <td class="py-2 px-4 border-b border-gray-300">{{ $item->unique_order }}</td>
                                    <td class="py-2 px-4 border-b border-gray-300">
                                        {{ $item->perangkat->produk->nama_produk ?? '-' }}</td>
                                    <td class="py-2 px-4 border-b border-gray-300">{{ formatTanggal($item->created_at) }}
                                    </td>
                                    <td class="py-2 px-4 border-b border-gray-300">
                                        {{ $item->statusTerakhir->keterangan ?? '-' }}</td>
                                    <td class="py-2 px-4 border-b border-gray-300 text-center">
                                        <a href="{{ url('user/pesanan/detail/' . $item->unique_order) }}"
                                            class="bg-purple-600 text-white px-3 py-1 rounded hover:bg-purple-700 transition">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button class="text-center text-purple-600 font-bold hover:text-purple-700" onclick="window.location.href='{{ url('user/pesanan') }}'">Lihat Semua</button>
                @endif
            </div>

        </div>
    </div>
@endsection
