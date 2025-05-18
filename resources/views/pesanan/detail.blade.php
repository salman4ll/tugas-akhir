@extends('layouts.blank')

@section('title', 'Product')

@section('content')
    <div class="bg-gray-100">
        <div class="mx-auto max-w-7xl px-8 py-10 min-h-screen text-gray-800">
            <div class="grid grid-cols-12 gap-[100px]">
                <div class="col col-span-4">
                    <div class="flex flex-col gap-8">
                        <div class="bg-gray-200 p-6 rounded-xl shadow-xl">
                            <img src="/assets/images/produk-link.png" class="w-full rounded-lg" alt="">
                        </div>
                        <div class="flex flex-col gap-5">
                            <p class="font-semibold text-3xl">{{ $order->perangkat->produk->nama_produk }}</p>
                            <div>
                                <p class="text-md">Pesanan dibuat tanggal {{ formatTanggal($order->created_at) }}</p>
                                <p class="text-md">ID Pesanan: {{ $order->unique_order }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-8">
                    <div class="flex gap-5 justify-around items-center text-center">
                        <button id="btnStatus" onclick="showStatusContent()"
                            class="py-3 border-b-purple-600 border-b-2 text-purple-600 font-semibold w-full">Status
                            Order</button>
                        <span>|</span>
                        <button id="btnRincian" onclick="showRincianContent()" class="w-full py-3">Rincian
                            Pemesanan</button>
                    </div>

                    {{-- Content Status Order --}}
                    <div id="statusContent" class="mt-10">
                        <ol class=" overflow-hidden space-y-8">
                            <li
                                class="relative flex-1 after:content-[''] after:w-0.5 after:h-full after:bg-gray-200 after:inline-block after:absolute after:-bottom-11 after:left-4 lg:after:left-5">
                                <div class="flex items-start font-medium w-full">
                                    <span
                                        class="w-8 h-8 bg-purple-50  border-2 border-purple-600 rounded-full flex justify-center items-center mr-3 text-sm text-purple-600 lg:w-10 lg:h-10">1</span>
                                    <div class="flex flex-col">
                                        <h4 class="text-lg text-purple-600 font-semibold">Pembayaran</h4>
                                        <span class="text-xl font-semibold mb-2">IDR86,000,000</span>
                                        <button class="bg-blue-400 text-white px-6 py-2 rounded-lg text-sm w-fit">
                                            Bayar Sekarang
                                        </button>
                                    </div>
                                </div>
                            </li>
                            <li
                                class="relative flex-1 after:content-['']  after:w-0.5 after:h-full  after:bg-gray-200 after:inline-block after:absolute after:-bottom-12 after:left-4 lg:after:left-5">
                                <a class="flex items-center font-medium w-full  ">
                                    <span
                                        class="w-8 h-8 bg-gray-50 border-2 border-gray-200 rounded-full flex justify-center items-center mr-3 text-sm  lg:w-10 lg:h-10">2</span>
                                    <div class="block">
                                        <h4 class="text-lg text-gray-900 font-semibold">Pengiriman</h4>
                                    </div>
                                </a>
                            </li>
                            <li
                                class="relative flex-1 after:content-['']  after:w-0.5 after:h-full  after:bg-gray-200 after:inline-block after:absolute after:-bottom-12 after:left-4 lg:after:left-5">
                                <a class="flex items-center font-medium w-full  ">
                                    <span
                                        class="w-8 h-8 bg-gray-50 border-2 border-gray-200 rounded-full flex justify-center items-center mr-3 text-sm  lg:w-10 lg:h-10">3</span>
                                    <div class="block">
                                        <h4 class="text-lg text-gray-900 font-semibold">Konfirmasi Pesanan</h4>
                                    </div>
                                </a>
                            </li>
                            <li
                                class="relative flex-1 after:content-['']  after:w-0.5 after:h-full  after:bg-gray-200 after:inline-block after:absolute after:-bottom-12 after:left-4 lg:after:left-5">
                                <a class="flex items-center font-medium w-full  ">
                                    <span
                                        class="w-8 h-8 bg-gray-50 border-2 border-gray-200 rounded-full flex justify-center items-center mr-3 text-sm  lg:w-10 lg:h-10">4</span>
                                    <div class="block">
                                        <h4 class="text-lg text-gray-900 font-semibold">Aktivasi</h4>
                                    </div>
                                </a>
                            </li>
                            <li
                                class="relative flex-1 after:content-['']  after:w-0.5 after:h-full  after:bg-gray-200 after:inline-block after:absolute after:-bottom-12 after:left-4 lg:after:left-5">
                                <a class="flex items-center font-medium w-full  ">
                                    <span
                                        class="w-8 h-8 bg-gray-50 border-2 border-gray-200 rounded-full flex justify-center items-center mr-3 text-sm  lg:w-10 lg:h-10">5</span>
                                    <div class="block">
                                        <h4 class="text-lg text-gray-900 font-semibold">Surat Pernyataan Aktivasi</h4>
                                    </div>
                                </a>
                            </li>
                            <li
                                class="relative flex-1 after:content-['']  after:w-0.5 after:h-full  after:bg-gray-200 after:inline-block after:absolute after:-bottom-12 after:left-4 lg:after:left-5">
                                <a class="flex items-center font-medium w-full  ">
                                    <span
                                        class="w-8 h-8 bg-gray-50 border-2 border-gray-200 rounded-full flex justify-center items-center mr-3 text-sm  lg:w-10 lg:h-10">6</span>
                                    <div class="block">
                                        <h4 class="text-lg text-gray-900 font-semibold">Pesanan Selesai</h4>
                                    </div>
                                </a>
                            </li>
                        </ol>
                    </div>
                    {{-- End Content Status Order --}}

                    {{-- Content Rincian Pemesanan --}}
                    <div id="rincianContent" class="mt-10 hidden">
                        <div class="flex flex-col gap-2 text-black">
                            <h2 class="font-semibold">Penerima</h2>
                            <div
                                class="flex flex-col py-2 px-4 w-full bg-gray-300 text-sm border border-[#2E2E2E] rounded-md focus-within:border-purple-500 focus-within:ring-2 focus-within:ring-purple-400 transition">
                                <label for="nama"
                                    class="duration-300 transform scale-75 top-0 left-4 z-10 origin-[0] peer-placeholder-shown:translate-y-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:text-white/70 peer-focus:scale-75 peer-focus:-translate-y-0">
                                    Nama<span class="text-red-500">*</span>
                                </label>
                                <span>
                                    {{ $order->cpCustomer->nama }}
                                </span>
                            </div>

                            <div
                                class="flex flex-col py-2 px-4 w-full bg-gray-300 text-sm border border-[#2E2E2E] rounded-md focus-within:border-purple-500 focus-within:ring-2 focus-within:ring-purple-400 transition">
                                <label for="telpon"
                                    class="duration-300 transform scale-75 top-0 left-4 z-10 origin-[0] peer-placeholder-shown:translate-y-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:text-white/70 peer-focus:scale-75 peer-focus:-translate-y-0">
                                    Nomor Telepon<span class="text-red-500">*</span>
                                </label>
                                <span>
                                    {{ $order->cpCustomer->no_telp }}
                                </span>
                            </div>

                            <div
                                class="flex flex-col py-2 px-4 w-full bg-gray-300 text-sm border border-[#2E2E2E] rounded-md focus-within:border-purple-500 focus-within:ring-2 focus-within:ring-purple-400 transition">
                                <label for="email"
                                    class="duration-300 transform scale-75 top-0 left-4 z-10 origin-[0] peer-placeholder-shown:translate-y-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:text-white/70 peer-focus:scale-75 peer-focus:-translate-y-0">
                                    Email
                                </label>
                                {{ $order->cpCustomer->email }}
                            </div>
                        </div>

                        @if ($order->jenis_pengiriman == 'ekspedisi')
                            <div class="flex flex-col gap-2 mt-6">
                                <h2 class="font-semibold">Alamat Pengiriman</h2>
                                <div class="grid grid-cols-12 gap-2">
                                    <div
                                        class="flex flex-col py-2 px-4 col-span-6 w-full bg-gray-300 text-sm border border-[#2E2E2E] rounded-md focus-within:border-purple-500 focus-within:ring-2 focus-within:ring-purple-400 transition">
                                        <label for="provinsi_id" class=" text-xs">
                                            Provinsi<span class="text-red-500">*</span>
                                        </label>
                                        <span>{{ $order->alamatCustomer->province->nama }}</span>
                                    </div>

                                    <div
                                        class="flex flex-col py-2 px-4 col-span-6 w-full bg-gray-300 text-sm border border-[#2E2E2E] rounded-md focus-within:border-purple-500 focus-within:ring-2 focus-within:ring-purple-400 transition">
                                        <label for="kabupaten_id" class=" text-xs">
                                            Kabupaten/kabupaten<span class="text-red-500">*</span>
                                        </label>
                                        <span>{{ $order->alamatCustomer->city->nama }}</span>
                                    </div>

                                    <div
                                        class="flex flex-col py-2 px-4 col-span-6 w-full bg-gray-300 text-sm border border-[#2E2E2E] rounded-md focus-within:border-purple-500 focus-within:ring-2 focus-within:ring-purple-400 transition">
                                        <label for="kecamatan_id" class=" text-xs">
                                            Kecamatan<span class="text-red-500">*</span>
                                        </label>
                                        <span>{{ $order->alamatCustomer->district->nama }}</span>
                                    </div>

                                    <div
                                        class="flex flex-col py-2 px-4 col-span-6 w-full bg-gray-300 text-sm border border-[#2E2E2E] rounded-md focus-within:border-purple-500 focus-within:ring-2 focus-within:ring-purple-400 transition">
                                        <label for="kelurahan_id" class=" text-xs">
                                            Kelurahan/Desa<span class="text-red-500">*</span>
                                        </label>
                                        <span>{{ $order->alamatCustomer->subdistrict->nama }}</span>
                                    </div>

                                    <div
                                        class="col col-span-4 flex flex-col py-2 px-4 w-full text-sm bg-gray-300 border border-[#2E2E2E] rounded-md appearance-none focus:outline-none focus:ring-2 focus:ring-blue-400 peer">
                                        <label for="rt" class="text-xs">
                                            RT<span class="text-red-500">*</span>
                                        </label>
                                        <span>{{ $order->alamatCustomer->rt }}</span>
                                    </div>

                                    <div
                                        class="col col-span-4 flex flex-col py-2 px-4 w-full text-sm bg-gray-300 border border-[#2E2E2E] rounded-md appearance-none focus:outline-none focus:ring-2 focus:ring-blue-400 peer">
                                        <label for="rw" class=" text-xs">
                                            RW<span class="text-red-500">*</span>
                                        </label>
                                        <span>{{ $order->alamatCustomer->rw }}</span>
                                    </div>

                                    <div
                                        class="col col-span-4 flex flex-col py-2 px-4 w-full text-sm  bg-gray-300 border border-[#2E2E2E] rounded-md appearance-none focus:outline-none focus:ring-2 focus:ring-blue-400 peer">
                                        <label for="kode_pos" class=" text-xs">
                                            Kode Pos<span class="text-red-500">*</span>
                                        </label>
                                        <span>{{ $order->alamatCustomer->kode_pos }}</span>
                                    </div>

                                    <div
                                        class="col col-span-12 flex flex-col py-2 px-4 w-full text-sm bg-gray-300 border border-[#2E2E2E] rounded-md appearance-none focus:outline-none focus:ring-2 focus:ring-blue-400 peer">
                                        <label for="pos" class=" text-xs">
                                            Detail Alamat<span class="text-red-500">*</span>
                                        </label>
                                        <span cols="30" row="3">{{ $order->alamatCustomer->alamat }}</span>
                                    </div>

                                    <div class="w-full col-span-12">
                                        <div id="map" class="w-full h-[250px]"></div>

                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2 mt-6">
                                <h2 class="font-semibold">Ekspedisi</h2>
                                <div class="flex justify-between gap-2">
                                    <span>{{ $order->metodePengiriman->courier_name }}</span>
                                    <span>{{ formatIDR($shippingCost) }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-col gap-2 mt-6">
                            <h2 class="font-semibold">Ringkasan Pembelian</h2>
                            <div class="flex justify-between gap-2">
                                <div class="flex flex-col">
                                    Perangkat
                                    <small class="text-[#B095F0]">{{ $order->perangkat->produk->nama_produk }} -
                                        {{ $order->perangkat->nama_perangkat }}</small>
                                </div>
                                <span>{{ formatIDR($hargaPerangkat) }}</span>
                            </div>
                            <div class="flex justify-between gap-2">
                                <div class="flex flex-col">
                                    Perangkat
                                    <small class="text-[#B095F0]">{{ $order->layanan->nama_layanan }}</small>
                                </div>
                                <span>{{ formatIDR($hargaLayanan) }}</span>
                            </div>
                            <div class="flex justify-between gap-2">
                                <div class="flex flex-col">
                                    Pengiriman
                                </div>
                                <span>{{ formatIDR($shippingCost) }}</span>
                            </div>
                            <hr class="border-t-2 border-gray-500 my-3">
                            <div class="flex justify-between gap-2">
                                <div class="flex flex-col">
                                    Total Biaya
                                </div>
                                <span>{{ formatIDR($totalBiaya) }}</span>
                            </div>
                            <div class="flex justify-between gap-2">
                                <div class="flex flex-col">
                                    PPN 11%
                                </div>
                                <span>{{ formatIDR($ppn) }}</span>
                            </div>
                            <hr class="border-t-2 border-gray-500 my-3">
                            <div class="flex justify-between gap-2">
                                <div class="flex flex-col">
                                    Total Keseluruhan
                                </div>
                                <span>{{ formatIDR($totalPembayaran) }}</span>
                            </div>
                            <div class="md:col-span-6 col-span-12 flex flex-col gap-5">
                                <div class="bg-gray-300 w-full p-5 rounded-2xl flex flex-col gap-2">
                                    <p class="font-bold text-xl ">Ringkasan Pembelian</p>

                                    <div class="flex flex-row justify-between">
                                        @if ($status_perusahaan == 1)
                                            <p class="font-bold text-md">Wajib Pungut (WAPU)</p>
                                        @elseif ($status_perusahaan == 2)
                                            <p class="font-bold text-md">Non-Wajib Pungut (non-WAPU) tanpa potong PPH 23
                                            </p>
                                        @else
                                            <p class="font-bold text-md">Non-Wajib Pungut (non-WAPU) potong PPH 23</p>
                                        @endif

                                    </div>

                                    <hr class="bg-[#242134] h-0.5 rounded-sm">

                                    <p class="font-bold text-xl ">Ringkasan Pembayaran</p>

                                    @if ($status_perusahaan == 1)
                                        <div class="flex flex-row justify-between">
                                            <p class="font-bold text-md">Total Biaya</p>
                                            <p class="text-md font-bold" id="ringkasanTotalBiaya">
                                                {{ formatIDR($totalBiaya) }}</p>
                                        </div>
                                    @else
                                        <div class="flex flex-row justify-between">
                                            <p class="font-bold text-md">Total Keseluruhan</p>
                                            <p class="text-md font-bold" id="ringkasanTotalKeseluruhan">
                                                {{ formatIDR($totalPembayaran) }}</p>
                                        </div>
                                    @endif

                                    @if ($status_perusahaan == 1 || $status_perusahaan == 3)
                                        <div class="flex flex-row justify-between">
                                            <p class="font-bold text-md">PPH 23</p>
                                            <p class="text-md font-bold" id="pph">{{ formatIDR($pph) }}</p>
                                        </div>
                                    @endif
                                    <hr class="bg-[#242134] h-0.5 rounded-sm">

                                    <div class="flex flex-row justify-between">
                                        <p class="font-bold text-md">Total Pembayaran<span class="text-red-600">*</span>
                                        </p>
                                        <p class="text-xl font-bold" id="ringkasanTotalPembayaran">
                                            {{ formatIDR($ringkasanTotalPembayaran) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function initMap() {
                const lokasiCustomer = {
                    lat: {{ $order->alamatCustomer->latitude ?? 0 }},
                    lng: {{ $order->alamatCustomer->longitude ?? 0 }}
                };

                const map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 15,
                    center: lokasiCustomer
                });

                new google.maps.Marker({
                    position: lokasiCustomer,
                    map: map,
                    title: "Lokasi Customer"
                });
            }
        </script>
        <script
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAZJEfXn4JomPN0kP0TIlqL1Qr8AniNKIY&libraries=places&callback=initMap"
            async defer></script>
        </script>
        <script>
            const btnStatus = document.getElementById('btnStatus');
            const btnRincian = document.getElementById('btnRincian');
            const statusContent = document.getElementById('statusContent');
            const rincianContent = document.getElementById('rincianContent');

            function showStatusContent() {
                statusContent.classList.remove('hidden');
                rincianContent.classList.add('hidden');

                btnStatus.classList.add('border-b-2', 'border-b-purple-600', 'text-purple-600', 'font-semibold');
                btnRincian.classList.remove('border-b-2', 'border-b-purple-600', 'text-purple-600', 'font-semibold');
            }

            function showRincianContent() {
                rincianContent.classList.remove('hidden');
                statusContent.classList.add('hidden');

                btnRincian.classList.add('border-b-2', 'border-b-purple-600', 'text-purple-600', 'font-semibold');
                btnStatus.classList.remove('border-b-2', 'border-b-purple-600', 'text-purple-600', 'font-semibold');
            }
        </script>
    @endpush
@endsection
