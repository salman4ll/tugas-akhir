@extends('layouts.blank-admin')

@section('title', 'Dashboard')

@section('content')
    <div class="p-4">
        <div class="overflow-x-auto rounded-lg shadow-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                    <tr>
                        <th class="px-6 py-3 text-left">No</th>
                        <th class="px-6 py-3 text-left">Customer</th>
                        <th class="px-6 py-3 text-left">Tanggal</th>
                        <th class="px-6 py-3 text-left">Total</th>
                        <th class="px-6 py-3 text-left">Jenis Pengiriman</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($order as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">{{ ($order->currentPage() - 1) * $order->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4">{{ $item->customer->nama_perusahaan ?? '-' }}</td>
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($item->order_date)->format('d-m-Y') }}</td>
                            <td class="px-6 py-4">Rp{{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">{{ ucfirst(str_replace('_', ' ', $item->jenis_pengiriman)) }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusText = $item->statusTerakhir->status->nama ?? 'Belum ada status';
                                    $statusClass = match ($statusText) {
                                        'new_order' => 'bg-yellow-100 text-yellow-800',
                                        'process_order' => 'bg-blue-100 text-blue-800',
                                        'packed_order' => 'bg-purple-100 text-purple-800',
                                        'pickup_order' => 'bg-green-100 text-green-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                    {{ str_replace('_', ' ', $statusText) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 relative">
                                <div class="relative inline-block text-left">
                                    <button type="button" onclick="toggleDropdown(this)"
                                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded text-sm focus:outline-none">
                                        Aksi
                                    </button>

                                    <div
                                        class="dropdown-menu hidden absolute z-10 mt-2 w-32 bg-white border border-gray-200 rounded shadow-md">
                                        <button onclick="showOrderDetail('{{ $item->unique_order }}')"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Detail</button>


                                        @php
                                            $statusNow = $item->statusTerakhir->status->status_code ?? null;
                                        @endphp

                                        @if ($statusNow === 'new_order')
                                            <form method="POST"
                                                action="{{ route('admin.pesanan.updateStatus', $item->unique_order) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="new_order">
                                                <button type="submit"
                                                    class="w-full text-left px-4 py-2 text-sm text-yellow-700 hover:bg-yellow-100">
                                                    Proses
                                                </button>
                                            </form>
                                        @elseif ($statusNow === 'process_order')
                                            <form method="POST"
                                                action="{{ route('admin.pesanan.updateStatus', $item->unique_order) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="process_order">
                                                <button type="submit"
                                                    class="w-full text-left px-4 py-2 text-sm text-blue-700 hover:bg-blue-100">
                                                    Packing
                                                </button>
                                            </form>
                                        @elseif ($statusNow === 'packed_order')
                                            <button type="button" onclick="createShipping('{{ $item->unique_order }}')"
                                                class="w-full text-left px-4 py-2 text-sm text-green-700 hover:bg-green-100">
                                                Pickup
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div id="orderDetailModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white w-11/12 max-w-2xl p-6 rounded shadow-lg relative">
                <button onclick="closeModal()"
                    class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-xl">&times;</button>
                <h2 class="text-lg font-semibold mb-4">Detail Order</h2>
                <div id="orderDetailContent" class="text-sm space-y-2">
                    <p class="text-gray-500">Loading...</p>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-between items-center">
            {{-- Tombol Sebelumnya --}}
            @if ($order->onFirstPage())
                <span class="px-4 py-2 text-gray-400 cursor-not-allowed">« Sebelumnya</span>
            @else
                <a href="{{ $order->previousPageUrl() }}"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded">« Sebelumnya</a>
            @endif

            {{-- Info Halaman --}}
            <span class="text-sm text-gray-600">
                Halaman {{ $order->currentPage() }} dari {{ $order->lastPage() }}
            </span>

            {{-- Tombol Selanjutnya --}}
            @if ($order->hasMorePages())
                <a href="{{ $order->nextPageUrl() }}"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded">Selanjutnya »</a>
            @else
                <span class="px-4 py-2 text-gray-400 cursor-not-allowed">Selanjutnya »</span>
            @endif
        </div>

    </div>
    <script>
        function toggleDropdown(button) {
            const dropdown = button.nextElementSibling;
            dropdown.classList.toggle('hidden');
            document.querySelectorAll('.dropdown-menu').forEach(el => {
                if (el !== dropdown) el.classList.add('hidden');
            });
        }

        document.addEventListener('click', function(event) {
            const isDropdownButton = event.target.closest('[onclick="toggleDropdown(this)"]');
            if (!isDropdownButton) {
                document.querySelectorAll('.dropdown-menu').forEach(el => el.classList.add('hidden'));
            }
        });
    </script>

    <script>
        const token = "{{ session('auth_token') }}"

        function createShipping(orderId) {
            fetch('/api/create-shipping', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Authorization': 'Bearer ' + token
                    },
                    body: JSON.stringify({
                        order_id: orderId
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal membuat shipping');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Shipping created:', data);
                    // Refresh halaman
                    location.reload();
                })
                .catch(error => {
                    alert('Terjadi kesalahan: ' + error.message);
                });
        }
    </script>

    <script>
        function showOrderDetail(orderId) {
            const modal = document.getElementById('orderDetailModal');
            const content = document.getElementById('orderDetailContent');
            const token = "{{ session('auth_token') }}"

            // Tampilkan loading
            content.innerHTML = '<p class="text-gray-500">Loading...</p>';
            modal.classList.remove('hidden');

            fetch(`/api/orders/${orderId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal mengambil data');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const order = data.data;

                        const shippingAddressHTML = order.order.jenis_pengiriman === "Ekspedisi" ? `
                                <span class="font-bold text-lg">Alamat Pengiriman:</span>
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="flex flex-col">
                                        <span class="font-semibold">Provinsi:</span>
                                        <span>${order.order.alamat_pengiriman.provinsi}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-semibold">Kabupaten/Kota:</span>
                                        <span>${order.order.alamat_pengiriman.kabupaten}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-semibold">Kecamatan:</span>
                                        <span>${order.order.alamat_pengiriman.kecamatan}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-semibold">Kelurahan:</span>
                                        <span>${order.order.alamat_pengiriman.kelurahan}</span>
                                    </div>
                                    <div class="flex flex-col col-span-2">
                                        <span class="font-semibold">Alamat Lengkap:</span>
                                        <span>${order.order.alamat_pengiriman.alamat_lengkap}</span>
                                    </div>
                                </div>
                        ` : '';

                        content.innerHTML = `
                        <div class="flex flex-col">
                            <span class="font-bold text-lg">Informasi Perusahaan</span>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="flex flex-col">
                                    <span class="font-semibold">Username:</span>
                                    <span id="username">${order.customer.username}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Nama Perusahaan:</span>
                                    <span id="companyName">${order.customer.nama}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Email Perusahaan:</span>
                                    <span id="companyEmail">${order.customer.email}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Nomor NPWP Perusahaan:</span>
                                    <span id="companyNpwp">${order.customer.npwp}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Telepon:</span>
                                    <span id="companyPhone">${order.customer.no_telp}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Status Perusahaan:</span>
                                    <span id="companyStatus">${order.customer.status_perusahaan}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Provinsi:</span>
                                    <span id="companyProvince">${order.customer.alamat.provinsi}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Kabupaten/Kota:</span>
                                    <span id="companyCity">${order.customer.alamat.kabupaten}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Kecamatan:</span>
                                    <span id="companyDistrict">${order.customer.alamat.kecamatan}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Kelurahan:</span>
                                    <span id="companySubdistrict">${order.customer.alamat.kelurahan}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Alamat:</span>
                                    <span id="companyAddress">${order.customer.alamat.alamat_lengkap}</span>
                                </div>
                            </div>
                            <span class="font-bold text-lg mt-4">Informasi Order</span>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="flex flex-col">
                                    <span class="font-semibold">ID Order:</span>
                                    <span id="orderId">${order.order.id}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Produk:</span>
                                    <span id="productName">${order.order.produk}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Layanan:</span>
                                    <span id="serviceType">${order.order.layanan}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Nama Penerima:</span>
                                    <span id="recipientName">${order.order.penerima}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Telepon Penerima:</span>
                                    <span id="recipientPhone">${order.order.no_telp_penerima}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Jenis Pengiriman:</span>
                                    <span id="shippingType">${order.order.jenis_pengiriman}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Total Harga:</span>
                                    <span id="totalPrice">${order.order.harga}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Tanggal Order:</span>
                                    <span id="orderDate">${order.order.tanggal_pemesanan}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Status Order:</span>
                                    <span id="orderStatus">${order.order.status_terakhir}</span>
                                </div>
                            </div>
                            ${shippingAddressHTML}
                        </div>
                    `;
                    } else {
                        content.innerHTML = '<p class="text-red-500">Data tidak ditemukan</p>';
                    }
                })
                .catch(error => {
                    content.innerHTML = '<p class="text-red-500">Gagal memuat data</p>';
                });
        }

        function closeModal() {
            document.getElementById('orderDetailModal').classList.add('hidden');
        }
    </script>


@endsection
