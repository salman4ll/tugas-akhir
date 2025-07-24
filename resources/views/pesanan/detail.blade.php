@extends('layouts.blank-dashboard')

@section('title', 'Product')

@section('content')
    <div class="bg-gray-100">
        <div class="mx-auto max-w-7xl px-8 py-10 min-h-screen text-gray-800">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 md:gap-[100px]">
                <div class="col md:col-span-4">
                    <div class="flex flex-col gap-8">
                        <div class="bg-gray-200 p-6 rounded-xl shadow-xl">
                            <img src="{{ asset('assets/images/' . $order->perangkat->produk->image) }}"
                                class="w-full rounded-lg" alt="">
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
                <div class="col md:col-span-8">
                    <div class="flex gap-5 justify-around items-center text-center">
                        <button id="btnStatus" onclick="showStatusContent()"
                            class="py-3 border-b-purple-600 border-b-2 text-purple-600 font-semibold w-full">Status
                            Order</button>
                        <span>|</span>
                        <button id="btnRincian" onclick="showRincianContent()" class="w-full py-3">Rincian
                            Pemesanan</button>
                    </div>

                    <div id="statusContent" class="mt-10">
                        @if ($activeStep == 5)
                            {{-- Step: Pesanan Dibatalkan --}}
                            <div class="flex items-start font-medium w-full">
                                <span
                                    class="w-8 h-8 bg-red-50 border-red-600 text-red-600 border-2 rounded-full flex justify-center items-center mr-3 text-sm lg:w-10 lg:h-10">!</span>
                                <div class="flex flex-col gap-3">
                                    <h4 class="text-lg text-red-600 font-semibold">Pesanan Dibatalkan</h4>
                                    <span class="text-gray-600">Pesanan ini telah dibatalkan. Jika Anda merasa ini adalah
                                        kesalahan, silakan hubungi Customer Service kami.</span>
                                    @if (!empty($order->statusTerakhir->keterangan))
                                        <span class="text-sm text-red-500 italic">
                                            Alasan pembatalan: {{ $order->statusTerakhir->keterangan }}
                                        </span>
                                    @endif
                                    <a href="https://wa.me/6281214931661" target="_blank"
                                        class="flex items-center justify-center gap-2 w-60 px-4 py-2 rounded bg-green-500 hover:bg-green-600 text-white transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M20.52 3.48A11.78 11.78 0 0 0 12 0C5.37 0 0 5.37 0 12c0 2.11.55 4.17 1.6 5.98L0 24l6.28-1.63a11.9 11.9 0 0 0 5.72 1.47h.01c6.63 0 12-5.37 12-12 0-3.19-1.25-6.18-3.48-8.52zM12 22c-1.64 0-3.25-.42-4.68-1.21l-.34-.19-3.73.97 1-3.63-.22-.37A9.94 9.94 0 0 1 2 12c0-5.52 4.48-10 10-10 2.67 0 5.18 1.04 7.07 2.93A9.94 9.94 0 0 1 22 12c0 5.52-4.48 10-10 10zm5.05-7.61c-.28-.14-1.65-.82-1.91-.91s-.44-.14-.62.14c-.18.28-.71.91-.87 1.1-.16.18-.32.2-.6.07-.28-.14-1.17-.43-2.24-1.39-.83-.74-1.39-1.65-1.55-1.93-.16-.28-.02-.43.12-.57.13-.13.28-.32.42-.48.14-.16.18-.28.28-.46.09-.18.05-.34-.02-.48s-.62-1.49-.85-2.04c-.22-.52-.44-.45-.62-.46l-.52-.01c-.18 0-.48.07-.73.34s-.96.94-.96 2.3c0 1.36.99 2.68 1.13 2.87.14.18 1.94 2.96 4.7 4.15.66.29 1.18.46 1.58.59.66.21 1.26.18 1.74.11.53-.08 1.65-.67 1.88-1.32.23-.66.23-1.23.16-1.32-.07-.09-.25-.14-.53-.28z" />
                                        </svg>
                                        Hubungi via WhatsApp
                                    </a>
                                </div>
                            </div>
                        @else
                            <ol class=" space-y-8">
                                {{-- Step 1: Pembayaran --}}
                                <li
                                    class="relative flex-1 after:content-[''] after:w-0.5 after:h-full {{ $activeStep > 1 ? 'after:bg-purple-600' : 'after:bg-gray-200' }} after:inline-block after:absolute after:-bottom-10 after:left-4 lg:after:left-5">
                                    <div class="flex items-start font-medium w-full">
                                        <span
                                            class="w-8 h-8 {{ $activeStep >= 1 ? 'bg-purple-50 border-purple-600 text-purple-600' : 'bg-gray-50 border-gray-200 text-gray-400' }} border-2 rounded-full flex justify-center items-center mr-3 text-sm lg:w-10 lg:h-10">1</span>
                                        <div class="flex flex-col">
                                            <h4
                                                class="text-lg {{ $activeStep >= 1 ? 'text-purple-600' : 'text-gray-900' }} font-semibold">
                                                Pembayaran
                                            </h4>
                                            <span
                                                class="text-xl font-semibold mb-2">{{ formatIDR($order->total_harga) }}</span>

                                            @if ($activeStep === 1 && $statusId == 1)
                                                <button
                                                    class="text-white w-60 px-4 py-2 rounded bg-purple-600 hover:bg-purple-700 transition"
                                                    onclick="window.location.href = '{{ $order->payment_url }}'">Bayar
                                                    Sekarang</button>
                                            @elseif ($activeStep === 1 && $statusId !== 1)
                                                <span class="font-medium">Pembayaran berhasil. Invoice telah dikirim ke
                                                    email <span
                                                        class="font-semibold">{{ $order->cpCustomer->email }}</span>.</span>
                                            @endif
                                        </div>
                                    </div>
                                </li>

                                {{-- Step 2: Pengiriman --}}
                                <li
                                    class="relative flex-1 after:content-[''] after:w-0.5 after:h-full {{ $activeStep >= 2 ? 'after:bg-purple-600' : 'after:bg-gray-200' }} after:inline-block after:absolute after:-bottom-10 after:left-4 lg:after:left-5">
                                    <a class="flex font-medium w-full">
                                        <span
                                            class="w-8 h-8 {{ $activeStep >= 2 ? 'bg-purple-50 border-purple-600 text-purple-600' : 'bg-gray-50 border-gray-200 text-gray-400' }} border-2 rounded-full flex justify-center items-center mr-3 text-sm lg:w-10 lg:h-10">2</span>
                                        <div class="flex flex-col">
                                            <h4
                                                class="text-lg {{ $activeStep >= 2 ? 'text-purple-600' : 'text-gray-900' }} font-semibold">
                                                Pengiriman
                                            </h4>
                                            @if ($activeStep >= 2)
                                                @if($activeStep == 2)
                                                    <span class="text-gray-600">
                                                        {{ $order->statusTerakhir?->status?->nama ?? 'Status Tidak Diketahui' }}
                                                    </span>
                                                @endif
                                                @if ($order->jenis_pengiriman === 'ambil_ditempat')
                                                    @if ($order->statusTerakhir?->status?->id == 10)
                                                        <p class="text-gray-600 mb-2">Pesanan Anda siap diambil di lokasi
                                                            berikut:</p>
                                                        <div class="p-3 border rounded bg-gray-50 text-sm text-gray-700">
                                                            {{ $alamatPengambilan }}
                                                        </div>
                                                    @elseif ($order->statusTerakhir?->status?->id == 11)
                                                        <p class="text-gray-600 mb-2 text-sm">Pesanan sudah Anda ambil. Silahkan melakukan konfirmasi</p>
                                                    @endif
                                                @elseif ($order->jenis_pengiriman === 'ekspedisi' && $order->trackingOrder->isNotEmpty())
                                                    <small class="text-gray-500">Dikirim pada
                                                        {{ optional($order->trackingOrder->first())->created_at ? formatTanggal($order->trackingOrder->first()->created_at) : '-' }}</small>
                                                    Paling Lambat tiba
                                                    {{ $order->metodePengiriman->duration_estimate }}</small>
                                                    <div class="flex items-center space-x-2">
                                                        <span>No resi: <span
                                                                id="nomorResi">{{ $order->nomor_resi }}</span></span>
                                                        <button onclick="copyResi()"
                                                            class="text-gray-500 hover:text-gray-700" title="Salin Resi">
                                                            <!-- SVG -->
                                                        </button>
                                                    </div>
                                                    <span>{{ $order->trackingOrder->last()->note ?? '-' }}</span>
                                                    <button id="trackOrderBtn"
                                                        class="w-auto underline text-[#3399FE] flex">Lacak Pesanan
                                                        Mu</button>
                                                @endif
                                            @endif
                                        </div>
                                    </a>
                                </li>

                                {{-- Step 3: Konfirmasi Pesanan --}}
                                <li
                                    class="relative flex-1 after:content-[''] after:w-0.5 after:h-full {{ $activeStep >= 3 ? 'after:bg-purple-600' : 'after:bg-gray-200' }} after:inline-block after:absolute after:-bottom-10 after:left-4 lg:after:left-5">
                                    <a class="flex font-medium w-full">
                                        <span
                                            class="w-8 h-8 {{ $activeStep >= 3 ? 'bg-purple-50 border-purple-600 text-purple-600' : 'bg-gray-50 border-gray-200 text-gray-400' }} border-2 rounded-full flex justify-center items-center mr-3 text-sm lg:w-10 lg:h-10">3</span>
                                        <div class="flex flex-col">
                                            <h4
                                                class="text-lg {{ $activeStep >= 3 ? 'text-purple-600' : 'text-gray-900' }} font-semibold">
                                                Konfirmasi Pesanan</h4>
                                            @if ($activeStep >= 3)
                                                <small class="text-gray-500 mb-4">Sampai pada
                                                    {{ formatTanggal($waktuPengirimanSampai) }}</small>

                                                @if ($activeStep == 3)
                                                    <div class="border border-dashed border-gray-400 p-3 flex flex-col items-center justify-center space-y-2 cursor-pointer w-fit rounded-md"
                                                        onclick="document.getElementById('imageUpload').click()"
                                                        id="uploadContainer">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" class="text-gray-500"
                                                            id="uploadIcon">
                                                            <path fill="currentColor"
                                                                d="m9.828 5l-2 2H4v12h16V7h-3.828l-2-2zM9 3h6l2 2h4a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h4zm3 15a5.5 5.5 0 1 1 0-11a5.5 5.5 0 0 1 0 11m0-2a3.5 3.5 0 1 0 0-7a3.5 3.5 0 0 0 0 7" />
                                                        </svg>
                                                        <input type="file" id="imageUpload" class="hidden"
                                                            accept="image/*" />
                                                    </div>
                                                @elseif($activeStep > 3)
                                                    <div
                                                        class="border border-dashed border-gray-400 p-3 flex flex-col items-center justify-center space-y-2 cursor-pointer w-fit rounded-md">
                                                        <img src="{{ Storage::url($order->confirmation_image) }}"
                                                            alt="Bukti Konfirmasi" class=" object-cover rounded-md">
                                                    </div>
                                                @endif

                                                <small class="text-gray-500 mt-4">Pesanan akan terkonfirmasi secara otomatis
                                                    setelah
                                                    1x24 jam</small>
                                                <button onclick="handleConfirm()"
                                                    class=" text-white w-60 px-4 py-2 rounded {{ $activeStep == 3 ? 'bg-purple-600 hover:bg-purple-700 transition' : 'bg-gray-500 cursor-not-allowed' }}"
                                                    {{ $activeStep == 3 ? '' : 'disabled' }}>
                                                    Konfirmasi Pesanan
                                                </button>

                                                <small class="text-gray-500 mt-4">Pesanan belum datang?</small>
                                                <button onclick="window.open('https://wa.me/6281214931661', '_blank')"
                                                    class="text-white w-60 px-4 py-2 rounded {{ $activeStep == 3 ? 'bg-purple-600 hover:bg-purple-700 transition' : 'bg-gray-500' }}"
                                                    {{ $activeStep == 3 ? '' : '' }}>
                                                    Hubungi AM
                                                </button>
                                            @endif
                                        </div>
                                    </a>
                                </li>

                                {{-- Step 4: Pesanan Selesai --}}
                                <li class="relative flex-1">
                                    <a class="flex items-center font-medium w-full">
                                        <span
                                            class="w-8 h-8 {{ $activeStep == 4 ? 'bg-purple-50 border-purple-600 text-purple-600' : 'bg-gray-50 border-gray-200 text-gray-400' }} border-2 rounded-full flex justify-center items-center mr-3 text-sm lg:w-10 lg:h-10">4</span>
                                        <div class="block">
                                            <h4
                                                class="text-lg {{ $activeStep == 4 ? 'text-purple-600' : 'text-gray-900' }} font-semibold">
                                                Pesanan Selesai</h4>
                                        </div>
                                    </a>
                                </li>
                            </ol>
                        @endif
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
                                    <span>{{ $order->metodePengiriman->courier_name }} -
                                        {{ $order->metodePengiriman->description }}</span>
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

    <div id="trackingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-lg w-full max-w-md relative">
            <h2 class="text-xl font-semibold mb-4">Tracking Pesanan</h2>
            <div id="trackingContent" class="flex flex-col-reverse gap-4 h-full overflow-y-auto px-2">
                <!-- Tracking data -->
            </div>

            <button id="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-black">&times;</button>
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
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiQ1_zaLft14YL7XWO5nPE32V8hMca5_g&libraries=places&callback=initMap"
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
        <script>
            const trackButton = document.getElementById('trackOrderBtn');
            const modal = document.getElementById('trackingModal');
            const closeModal = document.getElementById('closeModal');
            const content = document.getElementById('trackingContent');

            // Ganti nilai ini dengan PHP jika perlu (sisi server render)
            const orderId = "{{ $order->unique_order ?? 1128 }}";

            trackButton.addEventListener('click', async () => {
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

                    // Kosongkan konten sebelumnya
                    content.innerHTML = '';

                    if (result.status === 'success' && result.data.length) {
                        result.data.forEach((item, index) => {
                            const entry = document.createElement('div');
                            entry.classList.add('flex', 'items-start', 'gap-4', 'relative');

                            const isLatest = index === result.data.length -
                                1; // Data terakhir = paling atas = yang terbaru

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
                        content.innerHTML = '<p class="text-gray-600">Tracking data tidak ditemukan.</p>';
                    }

                    modal.classList.remove('hidden');

                } catch (error) {
                    content.innerHTML = '<p class="text-red-600">Terjadi kesalahan saat mengambil data.</p>';
                    modal.classList.remove('hidden');
                }
            });

            closeModal.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        </script>
        <script>
            function copyResi() {
                const resi = document.getElementById("nomorResi").innerText;
                navigator.clipboard.writeText(resi).then(function() {
                    const notif = document.getElementById("copyNotif");
                    notif.classList.remove("hidden");

                    // Sembunyikan setelah 2 detik
                    setTimeout(() => {
                        notif.classList.add("hidden");
                    }, 2000);
                }, function(err) {
                    alert('Gagal menyalin nomor resi.');
                    console.error('Error saat menyalin: ', err);
                });
            }
        </script>

        <script>
            const imageUpload = document.getElementById('imageUpload');
            const uploadContainer = document.getElementById('uploadContainer');
            const uploadIcon = document.getElementById('uploadIcon');

            imageUpload.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    // Hapus icon SVG
                    uploadIcon.style.display = 'none';

                    // Buat elemen img untuk preview
                    let img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = "Preview gambar";
                    img.className = "max-w-xs max-h-48 rounded-md object-contain";

                    // Hapus preview lama kalau ada, lalu tambahkan yang baru
                    const existingImg = uploadContainer.querySelector('img');
                    if (existingImg) {
                        uploadContainer.removeChild(existingImg);
                    }
                    uploadContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });

            async function handleConfirm() {
                const file = imageUpload.files[0];
                const token = "{{ session('auth_token') }}";

                const orderId = "{{ $order->unique_order }}";
                if (!file) {
                    alert("Silakan upload gambar terlebih dahulu.");
                    return;
                }

                const formData = new FormData();
                formData.append('order_id', orderId);
                formData.append('image', file);

                try {
                    const response = await fetch('/api/confirmation-order', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Authorization': 'Bearer ' + token
                        }
                    });

                    const result = await response.json();

                    if (response.ok) {
                        alert("Pesanan berhasil dikonfirmasi!");
                        //reload halaman atau lakukan tindakan lain
                        location.reload();
                    } else {
                        alert("Gagal mengonfirmasi pesanan.");
                        console.error(result);
                    }
                } catch (error) {
                    alert("Terjadi kesalahan saat mengirim data.");
                    console.error(error);
                }
            }
        </script>
    @endpush
@endsection
