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
                            <p class="font-semibold text-3xl">MangoeStar Lite</p>
                            <div>
                                <p class="text-md">Pesanan dibuat tanggal 20 Maret 2024</p>
                                <p class="text-md">ID Pesanan: D0195823500</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-8">
                    <div class="flex gap-5 justify-around items-center text-center">
                        <button id="btnStatus" onclick="showStatusContent()" class="py-3 border-b-purple-600 border-b-2 text-purple-600 font-semibold w-full">Status Order</button>
                        <span>|</span>
                        <button id="btnRincian" onclick="showRincianContent()" class="w-full py-3">Rincian Pemesanan</button>
                    </div>

                    {{-- Content Status Order --}}
                    <div id="statusContent" class="mt-10">
                        <ol class=" overflow-hidden space-y-8">
                            <li class="relative flex-1 after:content-[''] after:w-0.5 after:h-full after:bg-gray-200 after:inline-block after:absolute after:-bottom-11 after:left-4 lg:after:left-5">
                                <div class="flex items-start font-medium w-full">
                                    <span class="w-8 h-8 bg-purple-50  border-2 border-purple-600 rounded-full flex justify-center items-center mr-3 text-sm text-purple-600 lg:w-10 lg:h-10">1</span>
                                    <div class="flex flex-col">
                                        <h4 class="text-lg text-purple-600 font-semibold">Pembayaran</h4>
                                        <span class="text-xl font-semibold mb-2">IDR86,000,000</span>
                                        <button class="bg-blue-400 text-white px-6 py-2 rounded-lg text-sm w-fit">
                                            Bayar Sekarang
                                        </button>
                                    </div>
                                </div>
                            </li>                            
                            <li class="relative flex-1 after:content-['']  after:w-0.5 after:h-full  after:bg-gray-200 after:inline-block after:absolute after:-bottom-12 after:left-4 lg:after:left-5">
                                <a class="flex items-center font-medium w-full  ">
                                    <span class="w-8 h-8 bg-gray-50 border-2 border-gray-200 rounded-full flex justify-center items-center mr-3 text-sm  lg:w-10 lg:h-10">2</span>
                                    <div class="block">
                                        <h4 class="text-lg text-gray-900 font-semibold">Pengiriman</h4>
                                    </div>
                                </a>
                            </li>
                            <li class="relative flex-1 after:content-['']  after:w-0.5 after:h-full  after:bg-gray-200 after:inline-block after:absolute after:-bottom-12 after:left-4 lg:after:left-5">
                                <a class="flex items-center font-medium w-full  ">
                                    <span class="w-8 h-8 bg-gray-50 border-2 border-gray-200 rounded-full flex justify-center items-center mr-3 text-sm  lg:w-10 lg:h-10">3</span>
                                    <div class="block">
                                        <h4 class="text-lg text-gray-900 font-semibold">Konfirmasi Pesanan</h4>
                                    </div>
                                </a>
                            </li>
                            <li class="relative flex-1 after:content-['']  after:w-0.5 after:h-full  after:bg-gray-200 after:inline-block after:absolute after:-bottom-12 after:left-4 lg:after:left-5">
                                <a class="flex items-center font-medium w-full  ">
                                    <span class="w-8 h-8 bg-gray-50 border-2 border-gray-200 rounded-full flex justify-center items-center mr-3 text-sm  lg:w-10 lg:h-10">4</span>
                                    <div class="block">
                                        <h4 class="text-lg text-gray-900 font-semibold">Aktivasi</h4>
                                    </div>
                                </a>
                            </li>
                            <li class="relative flex-1 after:content-['']  after:w-0.5 after:h-full  after:bg-gray-200 after:inline-block after:absolute after:-bottom-12 after:left-4 lg:after:left-5">
                                <a class="flex items-center font-medium w-full  ">
                                    <span class="w-8 h-8 bg-gray-50 border-2 border-gray-200 rounded-full flex justify-center items-center mr-3 text-sm  lg:w-10 lg:h-10">5</span>
                                    <div class="block">
                                        <h4 class="text-lg text-gray-900 font-semibold">Surat Pernyataan Aktivasi</h4>
                                    </div>
                                </a>
                            </li>
                            <li class="relative flex-1 after:content-['']  after:w-0.5 after:h-full  after:bg-gray-200 after:inline-block after:absolute after:-bottom-12 after:left-4 lg:after:left-5">
                                <a class="flex items-center font-medium w-full  ">
                                    <span class="w-8 h-8 bg-gray-50 border-2 border-gray-200 rounded-full flex justify-center items-center mr-3 text-sm  lg:w-10 lg:h-10">6</span>
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
                                        <label for="kabupaten_id" class=" text-xs">
                                            Kabupaten/kabupaten<span class="text-red-500">*</span>
                                        </label>
                                        <select name="kabupaten_id" id="kabupaten_id" class="bg-transparent appearance-none"
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

                                    <div class="w-full">
                                        <iframe class="h-[250px]" src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d15853.629109087993!2d106.80437485!3d-6.5961984000000005!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sid!4v1747219720039!5m2!1sen!2sid" width="775px" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- End Content Rincian Pemesanan --}}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAZJEfXn4JomPN0kP0TIlqL1Qr8AniNKIY&libraries=places">
        </script>
        <script>
            const btnStatus = document.getElementById('btnStatus');
            const btnRincian = document.getElementById('btnRincian');
            const statusContent = document.getElementById('statusContent');
            const rincianContent = document.getElementById('rincianContent');

            function showStatusContent(){
                statusContent.classList.remove('hidden');
                rincianContent.classList.add('hidden');

                btnStatus.classList.add('border-b-2', 'border-b-purple-600', 'text-purple-600', 'font-semibold');
                btnRincian.classList.remove('border-b-2', 'border-b-purple-600', 'text-purple-600', 'font-semibold');
            }

            function showRincianContent(){
                rincianContent.classList.remove('hidden');
                statusContent.classList.add('hidden');

                btnRincian.classList.add('border-b-2', 'border-b-purple-600', 'text-purple-600', 'font-semibold');
                btnStatus.classList.remove('border-b-2', 'border-b-purple-600', 'text-purple-600', 'font-semibold');
            }
        </script>
    @endpush
@endsection