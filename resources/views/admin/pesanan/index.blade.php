@extends('layouts.blank-admin')

@section('title', 'Dashboard')

@section('content')
    <div class="p-4">
        <!-- Search Bar -->
        <div class="mb-4">
            <div class="flex items-center space-x-4 mb-4">
                {{-- Search Input --}}
                <div class="flex-1">
                    <input type="text" name="search" placeholder="Cari berdasarkan customer, tanggal, total, status..."
                        value="{{ request('search') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                {{-- Status Filter Dropdown --}}
                <form method="GET" action="{{ url()->current() }}">
                    <select name="status_group" onchange="this.form.submit()"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="belum_dibayar" {{ request('status_group') == 'belum_dibayar' ? 'selected' : '' }}>
                            Belum Dibayar</option>
                        <option value="diproses" {{ request('status_group') == 'diproses' ? 'selected' : '' }}>Diproses
                        </option>
                        <option value="dikirim" {{ request('status_group') == 'dikirim' ? 'selected' : '' }}>Dikirim
                        </option>
                        <option value="selesai" {{ request('status_group') == 'selesai' ? 'selected' : '' }}>Selesai
                        </option>
                    </select>
                </form>

                <button onclick="clearSearch()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                    Clear
                </button>
            </div>

        </div>

        <div class="overflow-x-auto rounded-lg shadow-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                    <tr>
                        <th class="px-6 py-3 text-left">No</th>
                        <th class="px-6 py-3 text-left">
                            <button onclick="sortTable('customer')" class="flex items-center hover:text-blue-600">
                                Customer
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <button onclick="sortTable('order_date')" class="flex items-center hover:text-blue-600">
                                Tanggal
                                <svg class="w-4 h-4 ml-1 {{ request('sort_by') == 'order_date' ? (request('sort_direction') == 'asc' ? 'rotate-180' : '') : '' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <button onclick="sortTable('total_harga')" class="flex items-center hover:text-blue-600">
                                Total
                                <svg class="w-4 h-4 ml-1 {{ request('sort_by') == 'total_harga' ? (request('sort_direction') == 'asc' ? 'rotate-180' : '') : '' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <button onclick="sortTable('jenis_pengiriman')" class="flex items-center hover:text-blue-600">
                                Jenis Pengiriman
                                <svg class="w-4 h-4 ml-1 {{ request('sort_by') == 'jenis_pengiriman' ? (request('sort_direction') == 'asc' ? 'rotate-180' : '') : '' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <button onclick="sortTable('status')" class="flex items-center hover:text-blue-600">
                                Status
                                <svg class="w-4 h-4 ml-1 {{ request('sort_by') == 'status' ? (request('sort_direction') == 'asc' ? 'rotate-180' : '') : '' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($order as $item)
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
                                            $statusId = $item->statusTerakhir->status->id ?? null;
                                        @endphp

                                        @if ($statusId >= 5 && $statusId <= 9 && $item->jenis_pengiriman === 'ekspedisi')
                                            <button
                                                class="trackOrderBtn block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                data-order-id="{{ $item->unique_order }}">
                                                Tracking
                                            </button>
                                        @endif

                                        @if ($statusId == 1 || $statusId == 2)
                                            <button type="button" onclick="showCancelModal('{{ $item->unique_order }}')"
                                                class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-100">
                                                Cancel Order
                                            </button>
                                        @endif

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
                                            @if ($item->jenis_pengiriman === 'ekspedisi')
                                                <button type="button"
                                                    onclick="createShipping('{{ $item->unique_order }}')"
                                                    class="w-full text-left px-4 py-2 text-sm text-green-700 hover:bg-green-100">
                                                    Siap Dikirim
                                                </button>
                                            @else
                                                <form method="POST"
                                                    action="{{ route('admin.pesanan.updateStatus', $item->unique_order) }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="packed_order">
                                                    <button type="submit"
                                                        class="w-full text-left px-4 py-2 text-sm text-purple-700 hover:bg-purple-100">
                                                        Siap Diambil
                                                    </button>
                                                </form>
                                            @endif
                                        @elseif ($statusNow === 'ready_for_pickup')
                                            <form method="POST"
                                                action="{{ route('admin.pesanan.updateStatus', $item->unique_order) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="ready_for_pickup">
                                                <button type="submit"
                                                    class="w-full text-left px-4 py-2 text-sm text-blue-700 hover:bg-blue-100">
                                                    Sudah Diambil
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                @if (request('search'))
                                    Tidak ada data yang sesuai dengan pencarian "{{ request('search') }}"
                                @else
                                    Tidak ada data pesanan
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="cancelOrderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Batalkan Pesanan</h3>
                            <button type="button" onclick="closeCancelModal()"
                                class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form id="cancelOrderForm" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Keterangan Pembatalan <span class="text-red-500">*</span>
                                </label>
                                <textarea id="keterangan" name="keterangan" rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Masukkan alasan pembatalan pesanan..." required></textarea>
                                <div id="keteranganError" class="text-red-500 text-sm mt-1 hidden"></div>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="closeCancelModal()"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                                    Cancel Order
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Detail Order -->
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
        @if ($order->hasPages())
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
                    @if ($order->total() > 0)
                        ({{ $order->total() }} total data)
                    @endif
                </span>

                {{-- Tombol Selanjutnya --}}
                @if ($order->hasMorePages())
                    <a href="{{ $order->nextPageUrl() }}"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded">Selanjutnya »</a>
                @else
                    <span class="px-4 py-2 text-gray-400 cursor-not-allowed">Selanjutnya »</span>
                @endif
            </div>
        @endif
    </div>

    <div id="trackingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-lg w-full max-w-md relative">
            <h2 class="text-xl font-semibold mb-4">Tracking Pesanan</h2>
            <div id="trackingContent" class="flex flex-col-reverse gap-4 h-full overflow-y-auto px-2">
                <!-- Tracking data -->
            </div>

            <button id="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-black">&times;</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('trackingModal');
            const closeModal = document.getElementById('closeModal');
            const content = document.getElementById('trackingContent');

            // Delegasi event untuk semua tombol tracking
            document.querySelectorAll('.trackOrderBtn').forEach(button => {
                button.addEventListener('click', async () => {
                    const orderId = button.getAttribute('data-order-id');

                    try {
                        const response = await fetch('/api/get-tracking', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                order_id: orderId
                            })
                        });

                        const result = await response.json();
                        content.innerHTML = '';

                        if (result.status === 'success' && result.data.length) {
                            result.data.forEach((item, index) => {
                                const entry = document.createElement('div');
                                entry.classList.add('flex', 'items-start', 'gap-4',
                                    'relative');

                                const isLatest = index === result.data.length - 1;
                                const date = new Date(item.created_at);
                                const formattedDate = date.toLocaleDateString('id-ID', {
                                    day: 'numeric',
                                    month: 'long',
                                    year: 'numeric'
                                });
                                const formattedTime = date.toLocaleTimeString('id-ID', {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: false
                                });

                                entry.innerHTML = `
                                <div class="flex flex-col items-center">
                                    <div class="w-3 h-3 rounded-full ${isLatest ? 'bg-green-500' : 'bg-purple-500'}"></div>
                                    ${index !== 0 ? '<div class="w-px bg-gray-300 flex-1 mt-1 mb-1"></div>' : '<div class="h-2"></div>'}
                                </div>
                                <div class="text-sm">
                                    <p class="font-medium text-gray-800">${formattedDate} ${formattedTime}</p>
                                    <p class="text-gray-600">${item.note}</p>
                                </div>
                            `;
                                content.appendChild(entry);
                            });
                        } else {
                            content.innerHTML =
                                '<p class="text-gray-600">Tracking data tidak ditemukan.</p>';
                        }

                        modal.classList.remove('hidden');
                    } catch (error) {
                        content.innerHTML =
                            '<p class="text-red-600">Terjadi kesalahan saat mengambil data.</p>';
                        modal.classList.remove('hidden');
                    }
                });
            });

            closeModal.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });
    </script>


    <!-- JavaScript -->
    <script>
        let debounceTimer;

        // Search with debounce
        document.getElementById('searchInput').addEventListener('input', function(e) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                performSearch(e.target.value);
            }, 500); // 500ms delay
        });

        function performSearch(searchTerm) {
            const url = new URL(window.location);
            if (searchTerm.trim() === '') {
                url.searchParams.delete('search');
            } else {
                url.searchParams.set('search', searchTerm);
            }
            url.searchParams.delete('page'); // Reset to first page
            window.location = url;
        }

        function clearSearch() {
            document.getElementById('searchInput').value = '';
            const url = new URL(window.location);
            url.searchParams.delete('search');
            url.searchParams.delete('page');
            window.location = url;
        }

        function sortTable(column) {
            const url = new URL(window.location);
            const currentSort = url.searchParams.get('sort_by');
            const currentDirection = url.searchParams.get('sort_direction');

            let newDirection = 'asc';
            if (currentSort === column && currentDirection === 'asc') {
                newDirection = 'desc';
            }

            url.searchParams.set('sort_by', column);
            url.searchParams.set('sort_direction', newDirection);
            url.searchParams.delete('page'); // Reset to first page

            window.location = url;
        }

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
                    location.reload();
                })
                .catch(error => {
                    alert('Terjadi kesalahan: ' + error.message);
                });
        }

        function showOrderDetail(orderId) {
            const modal = document.getElementById('orderDetailModal');
            const content = document.getElementById('orderDetailContent');
            const token = "{{ session('auth_token') }}"

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

    <script>
        function showCancelModal(orderId) {
            const modal = document.getElementById('cancelOrderModal');
            const form = document.getElementById('cancelOrderForm');
            const keteranganInput = document.getElementById('keterangan');
            const errorDiv = document.getElementById('keteranganError');

            // Set form action
            form.action = `/admin/orders/cancel/${orderId}`;

            // Clear previous values and errors
            keteranganInput.value = '';
            errorDiv.classList.add('hidden');
            errorDiv.textContent = '';

            // Show modal
            modal.classList.remove('hidden');

            // Focus on textarea
            setTimeout(() => {
                keteranganInput.focus();
            }, 100);
        }

        function closeCancelModal() {
            const modal = document.getElementById('cancelOrderModal');
            modal.classList.add('hidden');
        }

        // Handle form submission
        document.getElementById('cancelOrderForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const keteranganInput = document.getElementById('keterangan');
            const errorDiv = document.getElementById('keteranganError');
            const submitBtn = this.querySelector('button[type="submit"]');

            // Reset error state
            errorDiv.classList.add('hidden');
            errorDiv.textContent = '';

            // Validate keterangan
            if (!keteranganInput.value.trim()) {
                errorDiv.textContent = 'Keterangan harus diisi';
                errorDiv.classList.remove('hidden');
                keteranganInput.focus();
                return;
            }

            if (keteranganInput.value.trim().length > 255) {
                errorDiv.textContent = 'Keterangan maksimal 255 karakter';
                errorDiv.classList.remove('hidden');
                keteranganInput.focus();
                return;
            }

            // Show loading state
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Processing...';

            // Create FormData
            const formData = new FormData(this);

            // Submit form via fetch
            fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message (you can customize this)
                        alert('Pesanan berhasil dibatalkan');

                        // Close modal
                        closeCancelModal();

                        // Reload page or update UI
                        window.location.reload();
                    } else {
                        // Handle validation errors
                        if (data.errors && data.errors.keterangan) {
                            errorDiv.textContent = data.errors.keterangan[0];
                            errorDiv.classList.remove('hidden');
                        } else {
                            alert(data.message || 'Terjadi kesalahan');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memproses permintaan');
                })
                .finally(() => {
                    // Reset button state
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                });
        });

        // Close modal when clicking outside
        document.getElementById('cancelOrderModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCancelModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('cancelOrderModal');
                if (!modal.classList.contains('hidden')) {
                    closeCancelModal();
                }
            }
        });
    </script>

@endsection
