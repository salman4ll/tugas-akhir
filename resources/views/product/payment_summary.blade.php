@extends('layouts.blank')

@section('title', 'Product')

@section('content')
    <style>
        .ts-control,
        .ts-wrapper.single.input-active .ts-control {
            background-color: rgb(209 213 219 / var(--tw-bg-opacity, 1));
            border: 2px solid rgb(209 213 219 / var(--tw-bg-opacity, 1));
            color: white;
            padding: 0;
        }

        .ts-dropdown,
        .ts-control,
        .ts-control input {
            z-index: 9999;
        }

        .ts-dropdown,
        .ts-control,
        .ts-control input {
            color: black;
        }

        .full .ts-control {
            background-color: rgb(209 213 219 / var(--tw-bg-opacity, 1));
            border: 2px solid rgb(209 213 219 / var(--tw-bg-opacity, 1));
            color: black;
            font-weight: 500;
            padding: 0;
        }

        .ts-dropdown-content {
            background-color: rgb(209 213 219 / var(--tw-bg-opacity, 1));
            z-index: 9999;
        }

        .ts-wrapper.single .ts-control,
        .ts-wrapper.single .ts-control input {
            z-index: 1;
        }

        .ts-wrapper.single .ts-control input::placeholder {
            color: black;
        }

        .ts-dropdown, .ts-control, .ts-control input {
            color: black !important;
        }
    </style>
    <div class="">
        <div class="mx-auto max-w-7xl px-8 py-10 min-h-screen text-gray-800">
            <div class="flex flex-col gap-8">
                <div class="grid grid-cols-12 gap-8">
                    <div class="md:col-span-6 col-span-12 flex flex-col gap-2">
                        <p class="font-bold text-xl">Penerima</p>

                        <div class="flex flex-row gap-2 items-center">
                            <input type="checkbox" name="narahubung" id="narahubung">
                            <label for="narahubung" class="text-sm">Sama seperti narahubung</label>
                        </div>

                        <form action="{{ route('checkout') }}" method="POST" id="form-checkout">
                            @csrf
                            <div class="flex flex-col gap-2 text-black">
                                <div
                                    class="flex flex-col py-2 px-4 w-full bg-gray-300 text-sm border border-[#2E2E2E] rounded-md focus-within:border-purple-500 focus-within:ring-2 focus-within:ring-purple-400 transition">
                                    <label for="nama"
                                        class="duration-300 transform scale-75 top-0 left-4 z-10 origin-[0] peer-placeholder-shown:translate-y-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:text-white/70 peer-focus:scale-75 peer-focus:-translate-y-0">
                                        Nama<span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nama" id="nama"
                                        class="bg-transparent peer placeholder-transparent focus:outline-none"
                                        placeholder=" " required />
                                </div>

                                <div
                                    class="flex flex-col py-2 px-4 w-full bg-gray-300 text-sm border border-[#2E2E2E] rounded-md focus-within:border-purple-500 focus-within:ring-2 focus-within:ring-purple-400 transition">
                                    <label for="telpon"
                                        class="duration-300 transform scale-75 top-0 left-4 z-10 origin-[0] peer-placeholder-shown:translate-y-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:text-white/70 peer-focus:scale-75 peer-focus:-translate-y-0">
                                        Nomor Telepon<span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" name="telpon" id="telpon"
                                        class="bg-transparent peer placeholder-transparent focus:outline-none"
                                        placeholder=" " required />
                                </div>

                                <div
                                    class="flex flex-col py-2 px-4 w-full bg-gray-300 text-sm border border-[#2E2E2E] rounded-md focus-within:border-purple-500 focus-within:ring-2 focus-within:ring-purple-400 transition">
                                    <label for="email"
                                        class="duration-300 transform scale-75 top-0 left-4 z-10 origin-[0] peer-placeholder-shown:translate-y-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:text-white/70 peer-focus:scale-75 peer-focus:-translate-y-0">
                                        Email
                                    </label>
                                    <input type="email" name="email" id="email"
                                        class="bg-transparent peer placeholder-transparent focus:outline-none"
                                        placeholder=" " required />
                                </div>
                            </div>

                            <div class="flex flex-col gap-2 mt-6">
                                <p class="font-bold text-xl">Alamat Pengiriman</p>

                                <div class="flex flex-row gap-2 items-center">
                                    <input type="checkbox" name="narahubung" id="narahubung">
                                    <label for="narahubung" class="text-sm">Sama seperti alamat rumah/perusahaan</label>
                                </div>

                                <div class="grid grid-cols-12 gap-2">
                                    <div
                                        class="flex flex-col py-2 px-4 col-span-6 w-full bg-gray-300 text-sm border border-[#2E2E2E] rounded-md focus-within:border-purple-500 focus-within:ring-2 focus-within:ring-purple-400 transition">
                                        <label for="provinsi_id" class=" text-xs">
                                            Provinsi<span class="text-red-500">*</span>
                                        </label>
                                        <select name="provinsi_id" id="provinsi_id"
                                            class="bg-transparent p-0 m-0 appearance-none" required>
                                            <option value="" disabled selected>Pilih Provinsi...</option>
                                        </select>
                                    </div>

                                    <div
                                        class="flex flex-col py-2 px-4 col-span-6 w-full bg-gray-300 text-sm border border-[#2E2E2E] rounded-md focus-within:border-purple-500 focus-within:ring-2 focus-within:ring-purple-400 transition">
                                        <label for="kota_id" class=" text-xs">
                                            Kabupaten/Kota<span class="text-red-500">*</span>
                                        </label>
                                        <select name="kota_id" id="kota_id" class="bg-transparent appearance-none"
                                            required>
                                        </select>
                                    </div>

                                    <div
                                        class="flex flex-col py-2 px-4 col-span-6 w-full bg-gray-300 text-sm border border-[#2E2E2E] rounded-md focus-within:border-purple-500 focus-within:ring-2 focus-within:ring-purple-400 transition">
                                        <label for="kecamatan_id" class=" text-xs">
                                            Kecamatan<span class="text-red-500">*</span>
                                        </label>
                                        <select name="kecamatan_id" id="kecamatan_id" class="bg-transparent appearance-none"
                                            required>
                                        </select>
                                    </div>

                                    <div
                                        class="flex flex-col py-2 px-4 col-span-6 w-full bg-gray-300 text-sm border border-[#2E2E2E] rounded-md focus-within:border-purple-500 focus-within:ring-2 focus-within:ring-purple-400 transition">
                                        <label for="kelurahan_id" class=" text-xs">
                                            Kelurahan/Desa<span class="text-red-500">*</span>
                                        </label>
                                        <select name="kelurahan_id" id="kelurahan_id" class="bg-transparent appearance-none"
                                            required>
                                        </select>
                                    </div>

                                    <div
                                        class="col col-span-4 flex flex-col py-2 px-4 w-full text-sm text-white bg-[#242134] rounded-md border-0 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-400 peer">
                                        <label for="rt"
                                            class="text-sm text-white/70 duration-300 transform scale-75 top-0 left-4 z-10 origin-[0] peer-placeholder-shown:translate-y-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:text-white/70 peer-focus:scale-75 peer-focus:-translate-y-0">
                                            RT<span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="rt" id="rt" class="bg-transparent"
                                            placeholder=" " required />
                                    </div>

                                    <div
                                        class="col col-span-4 flex flex-col py-2 px-4 w-full text-sm text-white bg-[#242134] rounded-md border-0 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-400 peer">
                                        <label for="rw"
                                            class=" text-sm text-white/70 duration-300 transform scale-75 top-0 left-4 z-10 origin-[0] peer-placeholder-shown:translate-y-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:text-white/70 peer-focus:scale-75 peer-focus:-translate-y-0">
                                            RW<span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="rw" id="rw" class="bg-transparent"
                                            placeholder=" " required />
                                    </div>

                                    <div
                                        class="col col-span-4 flex flex-col py-2 px-4 w-full text-sm text-white bg-[#242134] rounded-md border-0 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-400 peer">
                                        <label for="kode_pos"
                                            class=" text-sm text-white/70 duration-300 transform scale-75 top-0 left-4 z-10 origin-[0] peer-placeholder-shown:translate-y-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:text-white/70 peer-focus:scale-75 peer-focus:-translate-y-0">
                                            Kode Pos<span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="kode_pos" id="kode_pos" class="bg-transparent"
                                            placeholder=" " required />
                                    </div>

                                    <div
                                        class="col col-span-12 flex flex-col py-2 px-4 w-full text-sm text-white bg-[#242134] rounded-md border-0 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-400 peer">
                                        <label for="pos"
                                            class=" text-sm text-white/70 duration-300 transform scale-75 top-0 left-4 z-10 origin-[0] peer-placeholder-shown:translate-y-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:text-white/70 peer-focus:scale-75 peer-focus:-translate-y-0">
                                            Detail Alamat<span class="text-red-500">*</span>
                                        </label>
                                        <textarea name="detail" id="detail" cols="30" rows="3" class="bg-transparent" placeholder=" "
                                            required></textarea>
                                    </div>

                                    <div class="relative w-full flex flex-col gap-2 col-span-full">

                                        <!-- MAPS MAIN -->
                                        <div id="main-map" class="w-full h-[250px]"></div>

                                        <!-- Hidden Input Untuk Simpan Latitude & Longitude -->
                                        <input type="hidden" id="latitude" name="latitude">
                                        <input type="hidden" id="longitude" name="longitude">

                                        <!-- Button Ubah Lokasi -->
                                        <div class="relative w-full">
                                            <button id="openModalButton" type="button"
                                                class="text-sm text-white bg-[#242134] py-3 px-10 rounded-2xl">
                                                Ubah Lokasi
                                            </button>
                                        </div>
                                    </div>

                                    <div class="relative  w-full group col-span-12 mt-6 gap-4 flex flex-col">
                                        <p class="font-bold text-xl">Pilih Ekspedisi</p>

                                        <label for="jne" class="flex flex-row justify-between">
                                            <div class="flex flex-row gap-2">
                                                <input type="radio" name="ekspedisi" id="jne">
                                                <div class="flex flex-col">
                                                    <label for="jne" id="nama_ekspedisi">JNE</label>
                                                    <label for="jne" id="estimasi"
                                                        class="text-sm text-gray-500">Estimasi sampai 2-4 Maret</label>
                                                </div>
                                            </div>

                                            <label for="jne" id="ongkir"
                                                class="text-md font-bold">IDR200.000</label>
                                        </label>

                                        <label for="sicepat" class="flex flex-row justify-between">
                                            <div class="flex flex-row gap-2">
                                                <input type="radio" name="ekspedisi" id="sicepat">
                                                <div class="flex flex-col">
                                                    <label for="sicepat" id="nama_ekspedisi">Sicepat</label>
                                                    <label for="sicepat" id="estimasi"
                                                        class="text-sm text-gray-500">Estimasi sampai 2-3 Maret</label>
                                                </div>
                                            </div>

                                            <label for="sicepat" id="ongkir"
                                                class="text-md font-bold">IDR220.000</label>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="md:col-span-6 col-span-12 flex flex-col gap-5">
                        <div class="flex flex-row justify-between items-center">
                            <p class="font-bold text-xl">Ringkasan Pembelian</p>
                        </div>

                        <div class="flex flex-col gap-2">
                            <div class="flex flex-row justify-between">
                                <div class="flex flex-col">
                                    <p class="text-md font-bold">Perangkat</p>
                                    <p class="text-sm text-gray-600">{{ $layanan->perangkat->nama_perangkat }}</p>
                                </div>

                                <p class="text-md font-bold">{{ $layanan->perangkat->formatted_price }}</p>
                            </div>

                            <div class="flex flex-row justify-between">
                                <div class="flex flex-col">
                                    <p class="text-md font-bold">Deposit Layanan</p>
                                    <p class="text-sm text-gray-600">{{ $layanan->nama_layanan }}</p>
                                </div>

                                <p class="text-md font-bold">{{ $layanan->formatted_price }}</p>
                            </div>

                            <div class="flex flex-row justify-between">
                                <p class="text-md font-bold">Pengiriman</p>

                                <p class="text-md font-bold">IDR200,000</p>
                            </div>

                            <div class="flex flex-row justify-between">
                                <p class="text-md font-bold">Asuransi Pengiriman</p>

                                <p class="text-md font-bold">IDR3,000</p>
                            </div>

                            <hr class="h-0.5 bg-[#242134] border-0 rounded" />

                            <div class="flex flex-row justify-between">
                                <p class="text-md font-bold">Total Biaya</p>

                                <p class="text-md font-bold">IDR61,084,590</p>
                            </div>

                            <div class="flex flex-row justify-between">
                                <p class="text-md font-bold">PPN 11%</p>

                                <p class="text-md font-bold">IDR6,719,305</p>
                            </div>

                            <hr class="h-0.5 bg-[#242134] border-0 rounded" />

                            <div class="flex flex-row justify-between">
                                <p class="text-md font-bold">Total Keseluruhan</p>

                                <p class="text-md font-bold">IDR67,803,894</p>
                            </div>
                        </div>

                        <div class="md:col-span-6 col-span-12 flex flex-col gap-5 text-white">
                            <div class="bg-[#242134] w-full p-5 rounded-2xl flex flex-col gap-2">
                                <p class="font-bold text-xl ">Ringkasan Pembelian</p>

                                <div class="flex flex-row justify-between">
                                    <p class="font-bold text-md">Wajib Pungut (WAPU)</p>
                                    <p>Edit</p>
                                </div>

                                <hr class="bg-white h-0.5 rounded-sm">

                                <p class="font-bold text-xl ">Ringkasan Pembayaran</p>

                                <div class="flex flex-row justify-between">
                                    <p class="font-bold text-md">Total Biaya</p>
                                    <p class="text-md font-bold">IDR61,084,590</p>
                                </div>

                                <div class="flex flex-row justify-between">
                                    <p class="font-bold text-md">PPH 23</p>
                                    <p class="text-md font-bold">-IDR347,200</p>
                                </div>

                                <hr class="bg-white h-0.5 rounded-sm">

                                <div class="flex flex-row justify-between">
                                    <p class="font-bold text-md">Total Pembayaran<span class="text-red-600">*</span></p>
                                    <p class="text-xl font-bold">IDR60,737,390</p>
                                </div>

                                <p class="text-sm text-gray-300">*Pembayaran dianggap lunas jika pelanggan sudah upload
                                    bukti potong PPN 11% dan PPH 23</p>

                                <div class="flex flex-row gap-2 items-center">
                                    <input type="checkbox" name="terms" id="terms">
                                    <label class="text-sm" for="terms">Saya menyetujui <span
                                            class="text-blue-300">Terms & Condition</span></label>
                                </div>

                                <button class="text-white text-md font-bold bg-purple-500 py-3 w-full rounded-2xl"
                                    id="button-checkout" type="submit" form="form-checkout">
                                    Checkout</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="mapModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 w-full p-0 m-0">
        <div class="relative bg-white rounded-2xl w-full max-w-[90%] md:max-w-[80%] lg:max-w-[60%] md:p-10 p-8">
            <!-- Search Box -->
            <input id="searchInput" type="text" placeholder="Cari lokasi..." class="border p-2 w-full">

            <!-- Map Container -->
            <div id="modalMap" class="flex w-full h-96"></div>

            <!-- Footer -->
            <div class="p-4 flex justify-end gap-2">
                <button id="cancelButton" class="bg-gray-400 text-white px-4 py-2 rounded" type="button">Batal</button>
                <button id="saveLocationButton" class="bg-blue-600 text-white px-4 py-2 rounded" type="button">Gunakan
                    Lokasi</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/js/order/address.js') }}"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAZJEfXn4JomPN0kP0TIlqL1Qr8AniNKIY&libraries=places">
        </script>
    @endpush
@endsection
