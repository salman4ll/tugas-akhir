@extends('layouts.blank')

@section('title', 'Product')

@section('content')
<div class="bg-gray-100">
    <div class="mx-auto max-w-7xl px-8 py-10 min-h-screen text-gray-800">
        <div class="flex flex-col gap-8">
            <div class="grid grid-cols-12 gap-8">
                <div class="md:col-span-6 col-span-12 flex flex-col gap-2">
                    <p class="font-bold text-xl">Penerima</p>

                    <div class="flex flex-row gap-2 items-center">
                        <input type="checkbox" name="narahubung" id="narahubung">
                        <label for="narahubung" class="text-sm">Sama seperti narahubung</label>
                    </div>

                    <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                        <div class="flex flex-col gap-2">
                            <div class="relative z-0 w-full group">
                                <input type="text" name="nama" id="nama" class="block py-3 pt-4 px-4 w-full text-sm text-white bg-[#242134] rounded-md border-0 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-400 peer" placeholder=" " required/>
                                <label for="nama" class="absolute text-sm text-white/70 duration-300 transform scale-75 top-0 left-4 z-10 origin-[0] peer-placeholder-shown:translate-y-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:text-white/70 peer-focus:scale-75 peer-focus:-translate-y-0">
                                    Nama<span class="text-red-500">*</span>
                                </label>
                            </div>

                            <div class="relative z-0 w-full group">
                                <input type="tel" name="telpon" id="telpon" class="block py-3 pt-4 px-4 w-full text-sm text-white bg-[#242134] rounded-md border-0 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-400 peer" placeholder=" " required/>
                                <label for="telpon" class="absolute text-sm text-white/70 duration-300 transform scale-75 top-0 left-4 z-10 origin-[0] peer-placeholder-shown:translate-y-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:text-white/70 peer-focus:scale-75 peer-focus:-translate-y-0">
                                    Nomor Telepon<span class="text-red-500">*</span>
                                </label>
                            </div>

                            <div class="relative z-0 w-full group">
                                <input type="email" name="email" id="email" class="block py-3 pt-4 px-4 w-full text-sm text-white bg-[#242134] rounded-md border-0 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-400 peer" placeholder=" "/>
                                <label for="email" class="absolute text-sm text-white/70 duration-300 transform scale-75 top-0 left-4 z-10 origin-[0] peer-placeholder-shown:translate-y-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:text-white/70 peer-focus:scale-75 peer-focus:-translate-y-0">
                                    Email
                                </label>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 mt-6">
                            <p class="font-bold text-xl">Alamat Pengiriman</p>

                            <div class="flex flex-row gap-2 items-center">
                                <input type="checkbox" name="narahubung" id="narahubung">
                                <label for="narahubung" class="text-sm">Sama seperti alamat rumah/perusahaan</label>
                            </div>

                            <div class="grid grid-cols-12 gap-2">
                                <div class="relative z-0 w-full group col-span-6">
                                    <select name="provinsi" id="provinsi" class="block py-3 pt-4 px-4 w-full text-sm text-white bg-[#242134] rounded-md border-0 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-400 peer" required>
                                        <option value="" disabled selected>Pilih Provinsi...</option>
                                        <option value="DKI Jakarta">DKI Jakarta</option>
                                        <option value="Jawa Barat">Jawa Barat</option>
                                        <option value="Jawa Timur">Jawa Timur</option>
                                        <option value="Bali">Bali</option>
                                        <option value="Yogyakarta">Yogyakarta</option>
                                    </select>
                                    <label for="provinsi" class="absolute text-sm text-white/70 duration-300 transform scale-75 top-0 left-4 z-10 origin-[0] peer-placeholder-shown:translate-y-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:text-white/70 peer-focus:scale-75 peer-focus:-translate-y-0">
                                        Provinsi<span class="text-red-500">*</span>
                                    </label>
                                </div>

                                <div class="relative z-0 w-full group col-span-6">
                                    <select name="kab" id="kab" class="block py-3 pt-4 px-4 w-full text-sm text-white bg-[#242134] rounded-md border-0 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-400 peer" required>
                                        <option value="" disabled selected>Pilih Kabupaten/Kota...</option>
                                        <option value="DKI Jakarta">DKI Jakarta</option>
                                        <option value="Jawa Barat">Jawa Barat</option>
                                        <option value="Jawa Timur">Jawa Timur</option>
                                        <option value="Bali">Bali</option>
                                        <option value="Yogyakarta">Yogyakarta</option>
                                    </select>
                                    <label for="kab" class="absolute text-sm text-white/70 duration-300 transform scale-75 top-0 left-4 z-10 origin-[0] peer-placeholder-shown:translate-y-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:text-white/70 peer-focus:scale-75 peer-focus:-translate-y-0">
                                        Kabupaten/Kota<span class="text-red-500">*</span>
                                    </label>
                                </div>

                                <div class="relative z-0 w-full group col-span-6">
                                    <select name="kec" id="kec" class="block py-3 pt-4 px-4 w-full text-sm text-white bg-[#242134] rounded-md border-0 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-400 peer" required>
                                        <option value="" disabled selected>Pilih Kecamatan...</option>
                                        <option value="DKI Jakarta">DKI Jakarta</option>
                                        <option value="Jawa Barat">Jawa Barat</option>
                                        <option value="Jawa Timur">Jawa Timur</option>
                                        <option value="Bali">Bali</option>
                                        <option value="Yogyakarta">Yogyakarta</option>
                                    </select>
                                    <label for="kec" class="absolute text-sm text-white/70 duration-300 transform scale-75 top-0 left-4 z-10 origin-[0] peer-placeholder-shown:translate-y-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:text-white/70 peer-focus:scale-75 peer-focus:-translate-y-0">
                                        Kecamatan<span class="text-red-500">*</span>
                                    </label>
                                </div>

                                <div class="relative z-0 w-full group col-span-6">
                                    <select name="kel" id="kel" class="block py-3 pt-4 px-4 w-full text-sm text-white bg-[#242134] rounded-md border-0 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-400 peer" required>
                                        <option value="" disabled selected>Pilih Kelurahan/Desa...</option>
                                        <option value="DKI Jakarta">DKI Jakarta</option>
                                        <option value="Jawa Barat">Jawa Barat</option>
                                        <option value="Jawa Timur">Jawa Timur</option>
                                        <option value="Bali">Bali</option>
                                        <option value="Yogyakarta">Yogyakarta</option>
                                    </select>
                                    <label for="kel" class="absolute text-sm text-white/70 duration-300 transform scale-75 top-0 left-4 z-10 origin-[0] peer-placeholder-shown:translate-y-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:text-white/70 peer-focus:scale-75 peer-focus:-translate-y-0">
                                        Kelurahan/Desa<span class="text-red-500">*</span>
                                    </label>
                                </div>

                                <div class="relative z-0 w-full group col-span-4">
                                    <input type="text" name="rt" id="rt" class="block py-3 pt-4 px-4 w-full text-sm text-white bg-[#242134] rounded-md border-0 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-400 peer" placeholder=" " required/>
                                    <label for="rt" class="absolute text-sm text-white/70 duration-300 transform scale-75 top-0 left-4 z-10 origin-[0] peer-placeholder-shown:translate-y-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:text-white/70 peer-focus:scale-75 peer-focus:-translate-y-0">
                                        RT<span class="text-red-500">*</span>
                                    </label>
                                </div>

                                <div class="relative z-0 w-full group col-span-4">
                                    <input type="text" name="rw" id="rw" class="block py-3 pt-4 px-4 w-full text-sm text-white bg-[#242134] rounded-md border-0 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-400 peer" placeholder=" " required/>
                                    <label for="rw" class="absolute text-sm text-white/70 duration-300 transform scale-75 top-0 left-4 z-10 origin-[0] peer-placeholder-shown:translate-y-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:text-white/70 peer-focus:scale-75 peer-focus:-translate-y-0">
                                        RW<span class="text-red-500">*</span>
                                    </label>
                                </div>

                                <div class="relative z-0 w-full group col-span-4">
                                    <input type="text" name="pos" id="pos" class="block py-3 pt-4 px-4 w-full text-sm text-white bg-[#242134] rounded-md border-0 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-400 peer" placeholder=" " required/>
                                    <label for="pos" class="absolute text-sm text-white/70 duration-300 transform scale-75 top-0 left-4 z-10 origin-[0] peer-placeholder-shown:translate-y-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:text-white/70 peer-focus:scale-75 peer-focus:-translate-y-0">
                                        Kode Pos<span class="text-red-500">*</span>
                                    </label>
                                </div>

                                <div class="relative z-0 w-full group col-span-12">
                                    <textarea name="detail" id="detail" cols="30" rows="3" class="block py-3 pt-4 px-4 w-full text-sm text-white bg-[#242134] rounded-md border-0 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-400 peer" placeholder=" " required></textarea>
                                    <label for="pos" class="absolute text-sm text-white/70 duration-300 transform scale-75 top-0 left-4 z-10 origin-[0] peer-placeholder-shown:translate-y-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:text-white/70 peer-focus:scale-75 peer-focus:-translate-y-0">
                                        Detail Alamat<span class="text-red-500">*</span>
                                    </label>
                                </div>

                                <div class="relative z-0 w-full group col-span-12 flex flex-col gap-2">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d15845.483490150391!2d106.75876386931151!3d-6.846066390644554!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sid!2sid!4v1744843477106!5m2!1sid!2sid" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

                                    <div class="relative z-0 w-full group col-span-4">
                                        <input type="text" name="rw" id="rw" class="block py-[55px] pt-5 px-4 w-full text-sm text-[#242134] border-2 border-[#242134] bg-transparent rounded-md appearance-none focus:outline-none focus:ring-2 focus:ring-blue-400 peer" placeholder=" " required/>
                                        <label for="rw" class="absolute text-sm text-[#242134] duration-300 transform scale-75 top-0 left-4 z-10 origin-[0] peer-placeholder-shown:translate-y-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:text-[#242134] peer-focus:scale-75 peer-focus:-translate-y-0">
                                            Maps<span class="text-red-500">*</span>
                                        </label>
                                        <button class="absolute text-sm text-white transform scale-75 top-[45px] left-4 z-10 origin-[0] bg-[#242134] py-3 px-10 rounded-2xl">Ubah</button>
                                    </div>
                                </div>
                                
                                <div class="relative z-0 w-full group col-span-6">
                                    <button class="text-white duration-300 transform scale-75 top-[45px] left-4 z-10 origin-[0] bg-purple-500 py-3 w-full rounded-2xl">Gunakan Alamat</button>
                                </div>

                                <div class="relative z-0 w-full group col-span-12 mt-6 gap-4 flex flex-col">
                                    <p class="font-bold text-xl">Pilih Ekspedisi</p>

                                    <label for="jne" class="flex flex-row justify-between">
                                        <div class="flex flex-row gap-2">
                                            <input type="radio" name="ekspedisi" id="jne">
                                            <div class="flex flex-col">
                                                <label for="jne" id="nama_ekspedisi">JNE</label>
                                                <label for="jne" id="estimasi" class="text-sm text-gray-500">Estimasi sampai 2-4 Maret</label>
                                            </div>
                                        </div>

                                        <label for="jne" id="ongkir" class="text-md font-bold">IDR200.000</label>
                                    </label>

                                    <label for="sicepat" class="flex flex-row justify-between">
                                        <div class="flex flex-row gap-2">
                                            <input type="radio" name="ekspedisi" id="sicepat">
                                            <div class="flex flex-col">
                                                <label for="sicepat" id="nama_ekspedisi">Sicepat</label>
                                                <label for="sicepat" id="estimasi" class="text-sm text-gray-500">Estimasi sampai 2-3 Maret</label>
                                            </div>
                                        </div>

                                        <label for="sicepat" id="ongkir" class="text-md font-bold">IDR220.000</label>
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
                                <p class="text-sm text-gray-600">Land Mobility</p>
                            </div>

                            <p class="text-md font-bold">IDR43,721,590</p>
                        </div>

                        <div class="flex flex-row justify-between">
                            <div class="flex flex-col">
                                <p class="text-md font-bold">Deposit Layanan</p>
                                <p class="text-sm text-gray-600">1 TB</p>
                            </div>

                            <p class="text-md font-bold">IDR17,160,000</p>
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

                            <p class="text-sm text-gray-300">*Pembayaran dianggap lunas jika pelanggan sudah upload bukti potong PPN 11% dan PPH 23</p>

                            <div class="flex flex-row gap-2 items-center">
                                <input type="checkbox" name="terms" id="terms">
                                <label class="text-sm" for="terms">Saya menyetujui <span class="text-blue-300">Terms & Condition</span></label>
                            </div>

                            <button class="text-white text-md font-bold bg-purple-500 py-3 w-full rounded-2xl">Pilih Metode Pembayaran</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
