@extends('layouts.blank-admin')

@section('title', 'Data Ekspedisi')

@section('content')
    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Daftar Ekspedisi</h2>
            <a href="{{ route('admin.ekspedisi.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Tambah Ekspedisi
            </a>
        </div>
        <div class="overflow-x-auto rounded-lg shadow-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                    <tr>
                        <th class="px-6 py-3 text-left">No</th>
                        <th class="px-6 py-3 text-left">Kode Kurir</th>
                        <th class="px-6 py-3 text-left">Nama Kurir</th>
                        <th class="px-6 py-3 text-left">Kode Layanan</th>
                        <th class="px-6 py-3 text-left">Nama Layanan</th>
                        <th class="px-6 py-3 text-left">Deskripsi</th>
                        <th class="px-6 py-3 text-left">Tipe Pengiriman</th>
                        <th class="px-6 py-3 text-left">Tipe Layanan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($shippingMethods as $index => $exp)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">{{ strtoupper($exp['courier_code']) }}</td>
                            <td class="px-6 py-4">{{ $exp['courier_name'] }}</td>
                            <td class="px-6 py-4">{{ $exp['courier_service_code'] }}</td>
                            <td class="px-6 py-4">{{ $exp['courier_service_name'] }}</td>
                            <td class="px-6 py-4">{{ $exp['description'] ?? '-' }}</td>
                            <td class="px-6 py-4">{{ ucfirst($exp['shipping_type']) }}</td>
                            <td class="px-6 py-4">{{ ucfirst($exp['service_type']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
