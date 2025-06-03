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

        .ts-dropdown,
        .ts-control,
        .ts-control input {
            color: black !important;
        }
    </style>
    <div class="">
        <div class="mx-auto max-w-7xl px-8 py-10 min-h-screen text-gray-800">
            <div class="flex flex-col gap-8">
                <div class="grid grid-cols-12 gap-8">
                    <div class="md:col-span-6 col-span-12 flex flex-col gap-2">
                        <p class="font-bold text-xl">Penerima</p>

                        <form action="{{ route('checkout') }}" method="POST" id="form-checkout">
                            @csrf
                            <input type="hidden" name="layanan_id" value="{{ $layanan->encrypted_id }}">
                            <input type="hidden" name="perangkat_id" value="{{ $layanan->perangkat->encrypted_id }}">
                            <div class="flex flex-row gap-2 items-center">
                                <input type="checkbox" name="isCpUser" id="isCpUser" value="1">
                                <label for="isCpUser" class="text-sm">Sama seperti narahubung</label>
                            </div>

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
                                    <label for="no_telp"
                                        class="duration-300 transform scale-75 top-0 left-4 z-10 origin-[0] peer-placeholder-shown:translate-y-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:text-white/70 peer-focus:scale-75 peer-focus:-translate-y-0">
                                        Nomor Telepon<span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" name="no_telp" id="no_telp"
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

                                <div class="flex flex-col gap-2">

                                    <div class="flex flex-row gap-2 items-center" id="ambilDitempatWrapper"
                                        style="display: none;">
                                        <input type="checkbox" name="checkbox_ambil_ditempat" id="checkbox_ambil_ditempat"
                                            value="1">
                                        <label for="checkbox_ambil_ditempat" class="text-sm">Ambil di Tempat</label>
                                    </div>


                                    <div id="alamatPengiriman" class="grid grid-cols-12 gap-2">
                                        <div class="flex flex-row col-span-12 gap-2 items-center">
                                            <input type="checkbox" name="isAddressUser" id="isAddressUser" value="1">
                                            <label for="isAddressUser" class="text-sm">Sama seperti alamat
                                                rumah/perusahaan</label>
                                        </div>
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
                                            <select name="kabupaten_id" id="kabupaten_id"
                                                class="bg-transparent appearance-none" required>
                                            </select>
                                        </div>

                                        <div
                                            class="flex flex-col py-2 px-4 col-span-6 w-full bg-gray-300 text-sm border border-[#2E2E2E] rounded-md focus-within:border-purple-500 focus-within:ring-2 focus-within:ring-purple-400 transition">
                                            <label for="kecamatan_id" class=" text-xs">
                                                Kecamatan<span class="text-red-500">*</span>
                                            </label>
                                            <select name="kecamatan_id" id="kecamatan_id"
                                                class="bg-transparent appearance-none" required>
                                            </select>
                                        </div>

                                        <div
                                            class="flex flex-col py-2 px-4 col-span-6 w-full bg-gray-300 text-sm border border-[#2E2E2E] rounded-md focus-within:border-purple-500 focus-within:ring-2 focus-within:ring-purple-400 transition">
                                            <label for="kelurahan_id" class=" text-xs">
                                                Kelurahan/Desa<span class="text-red-500">*</span>
                                            </label>
                                            <select name="kelurahan_id" id="kelurahan_id"
                                                class="bg-transparent appearance-none" required>
                                            </select>
                                        </div>

                                        <div
                                            class="flex flex-col py-2 px-4 col-span-4 w-full bg-gray-300 text-sm border border-[#2E2E2E] rounded-md focus-within:border-purple-500 focus-within:ring-2 focus-within:ring-purple-400 transition">
                                            <label for="rt" class="text-xs">
                                                RT<span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="rt" id="rt"
                                                class="bg-transparent appearance-none focus:outline-none" placeholder=" "
                                                required />
                                        </div>

                                        <div
                                            class="flex flex-col py-2 px-4 col-span-4 w-full bg-gray-300 text-sm border border-[#2E2E2E] rounded-md focus-within:border-purple-500 focus-within:ring-2 focus-within:ring-purple-400 transition">
                                            <label for="rw" class=" text-xs">
                                                RW<span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="rw" id="rw"
                                                class="bg-transparent appearance-none focus:outline-none" placeholder=" "
                                                required />
                                        </div>

                                        <div
                                            class="flex flex-col py-2 px-4 col-span-4 w-full bg-gray-300 text-sm border border-[#2E2E2E] rounded-md focus-within:border-purple-500 focus-within:ring-2 focus-within:ring-purple-400 transition">
                                            <label for="kode_pos" class=" text-xs">
                                                Kode Pos<span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="kode_pos" id="kode_pos"
                                                class="bg-transparent appearance-none focus:outline-none" placeholder=" "
                                                required />
                                        </div>

                                        <div
                                            class="flex flex-col py-2 px-4 col-span-12 w-full bg-gray-300 text-sm border border-[#2E2E2E] rounded-md focus-within:border-purple-500 focus-within:ring-2 focus-within:ring-purple-400 transition">
                                            <label for="pos" class=" text-xs">
                                                Detail Alamat<span class="text-red-500">*</span>
                                            </label>
                                            <textarea name="detail" id="detail" cols="30" rows="3"
                                                class="bg-transparent appearance-none focus:outline-none" placeholder=" " required></textarea>
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
                                                    class="text-sm text-white bg-purple-500 py-3 px-10 rounded-2xl">
                                                    Ubah Lokasi
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="relative w-full group col-span-12 mt-6 gap-4 flex flex-col">
                                        <p class="font-bold text-xl">Pilih Ekspedisi</p>

                                        <!-- Tetap tampil selalu -->
                                        <div id="opsiAmbilDiTempat">
                                            <label for="ambil_ditempat"
                                                class="flex flex-row justify-between items-start gap-4 cursor-pointer p-2 rounded hover:bg-gray-50">
                                                <div class="flex flex-row gap-3">
                                                    <input type="radio" name="ekspedisi" id="ambil_ditempat"
                                                        value="ambil_ditempat" data-price="0">
                                                    <div class="flex flex-col">
                                                        <label for="ambil_ditempat" class="font-medium text-base">Ambil di
                                                            Tempat</label>
                                                        <label for="ambil_ditempat" class="text-sm text-gray-500">Ambil
                                                            langsung di lokasi toko</label>
                                                    </div>
                                                </div>
                                                <div class="text-md font-bold text-green-600">Gratis</div>
                                            </label>
                                        </div>

                                        <!-- Ini hanya untuk ekspedisi hasil API -->
                                        <div id="courierList" class="flex flex-col gap-4">
                                            <!-- Isi dari fetch / API -->
                                        </div>

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
                                    <p class="text-sm text-gray-600">
                                        {{ $layanan->perangkat->nama_perangkat }}</p>
                                </div>

                                <p class="text-md font-bold" id="perangkatPrice"
                                    data-price="{{ $layanan->perangkat->harga_perangkat }}">
                                    {{ $layanan->perangkat->formatted_price }}</p>
                            </div>

                            <div class="flex flex-row justify-between">
                                <div class="flex flex-col">
                                    <p class="text-md font-bold">Deposit Layanan</p>
                                    <p class="text-sm text-gray-600">{{ $layanan->nama_layanan }}</p>
                                </div>

                                <p class="text-md font-bold" id="depositPrice"
                                    data-price="{{ $layanan->harga_layanan }}">{{ $layanan->formatted_price }}</p>
                            </div>

                            <div class="flex flex-row justify-between">
                                <p class="text-md font-bold">Pengiriman</p>

                                <p class="text-md font-bold" id="selectedCourierPrice">IDR0</p>
                            </div>

                            <hr class="h-0.5 bg-[#242134] border-0 rounded" />

                            <div class="flex flex-row justify-between">
                                <p class="text-md font-bold">Total Biaya</p>

                                <p class="text-md font-bold" id="totalBiaya">IDR0</p>
                            </div>

                            <div class="flex flex-row justify-between">
                                <p class="text-md font-bold">PPN 11%</p>

                                <p class="text-md font-bold" id="ppn">IDR0</p>
                            </div>

                            <hr class="h-0.5 bg-[#242134] border-0 rounded" />

                            <div class="flex flex-row justify-between">
                                <p class="text-md font-bold">Total Keseluruhan</p>

                                <p class="text-md font-bold" id="totalKeseluruhan">IDR0</p>
                            </div>
                        </div>



                        <div class="md:col-span-6 col-span-12 flex flex-col gap-5">
                            <div class="bg-gray-300 w-full p-5 rounded-2xl flex flex-col gap-2">
                                <p class="font-bold text-xl ">Ringkasan Pembelian</p>

                                <div class="flex flex-row justify-between">
                                    @if ($user->status_perusahaan == 1)
                                        <p class="font-bold text-md">Wajib Pungut (WAPU)</p>
                                    @elseif ($user->status_perusahaan == 2)
                                        <p class="font-bold text-md">Non-Wajib Pungut (non-WAPU) tanpa potong PPH 23</p>
                                    @else
                                        <p class="font-bold text-md">Non-Wajib Pungut (non-WAPU) potong PPH 23</p>
                                    @endif

                                </div>

                                <hr class="bg-[#242134] h-0.5 rounded-sm">

                                <p class="font-bold text-xl ">Ringkasan Pembayaran</p>

                                @if ($user->status_perusahaan == 1)
                                    <div class="flex flex-row justify-between">
                                        <p class="font-bold text-md">Total Biaya</p>
                                        <p class="text-md font-bold" id="ringkasanTotalBiaya"></p>
                                    </div>
                                @else
                                    <div class="flex flex-row justify-between">
                                        <p class="font-bold text-md">Total Keseluruhan</p>
                                        <p class="text-md font-bold" id="ringkasanTotalKeseluruhan"></p>
                                    </div>
                                @endif

                                @if ($user->status_perusahaan == 1 || $user->status_perusahaan == 3)
                                    <div class="flex flex-row justify-between">
                                        <p class="font-bold text-md">PPH 23</p>
                                        <p class="text-md font-bold" id="pph"></p>
                                    </div>
                                @endif
                                <hr class="bg-[#242134] h-0.5 rounded-sm">

                                <div class="flex flex-row justify-between">
                                    <p class="font-bold text-md">Total Pembayaran<span class="text-red-600">*</span></p>
                                    <p class="text-xl font-bold" id="ringkasanTotalPembayaran"></p>
                                </div>

                                <p class="text-sm text-gray-700">*Pembayaran dianggap lunas jika pelanggan sudah upload
                                    bukti potong PPN 11% dan PPH 23</p>

                                <div class="flex flex-row gap-2 items-center">
                                    <input type="checkbox" name="terms" id="terms"
                                        onclick="openModal(); this.checked = false;">
                                    <label class="text-sm" for="terms">
                                        Saya menyetujui
                                        <button type="button" class="text-blue-600 underline"
                                            onclick="openModal()">Terms & Condition</button>
                                    </label>
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

    <div id="termsModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 w-full p-0 m-0">
        <div class="relative bg-white rounded-2xl w-full max-w-[90%] md:max-w-[80%] lg:max-w-[30%] md:p-10 p-8">
            <h2 class="text-lg font-semibold mb-4">Terms & Condition</h2>
            <div class="h-96 overflow-y-auto p-2 text-sm flex flex-col gap-2">
                <!-- Bagian 1 -->
                <div class="border border-gray-200 rounded-lg">
                    <button onclick="toggleSection('section1')"
                        class="w-full text-left text-lg font-semibold bg-gray-100 px-4 py-3 rounded-t-lg">
                        Tentang Kebijakan Privasi
                    </button>
                    <div id="section1" class="hidden p-4 text-sm space-y-2">
                        <p>Situs <strong>mySatelite</strong> beserta aplikasi mySatelite (“Situs mySatelite”)
                            dikelola dan dioperasikan oleh perusahaan jasa telekomunikasi (“Perusahaan” atau “mySatelite”
                            atau “Kami”). Kami menerapkan Kebijakan Privasi ini untuk menghormati privasi Anda sebagai
                            pelanggan
                            Layanan atau jasa/produk mySatelite lainnya maupun sebagai pengguna Situs mySatelite (“Anda”)
                            dan mengatur penggunaan serta pelindungan informasi maupun data pribadi yang Anda berikan
                            dan/atau yang Kami peroleh ketika Anda menggunakan Situs mySatelite (“Data Pribadi”).</p>
                        <p>Dengan mengunduh, mengakses atau menggunakan Situs mySatelite, Anda dianggap telah membaca,
                            memahami, dan menyetujui Kebijakan Privasi serta memberikan persetujuan kepada Kami atas
                            penggunaan, pemrosesan, pengelolaan sesuai hukum yang berlaku atas Data Pribadi Anda.</p>
                    </div>
                </div>

                <!-- Bagian 2 -->
                <div class="border border-gray-200 rounded-lg">
                    <button onclick="toggleSection('section2')"
                        class="w-full text-left text-lg font-semibold bg-gray-100 px-4 py-3 rounded-t-lg">
                        Perolehan Data Pribadi
                    </button>
                    <div id="section2" class="hidden p-4 text-sm space-y-2">
                        <p>Kami dapat melakukan pengumpulan Data Pribadi Anda termasuk dan tidak terbatas kepada nama,
                            alamat, nomor telepon, tanggal lahir, usia, jenis kelamin, email, pekerjaan, nomor kartu
                            identitas/kependudukan, riwayat transaksi atau pembelian, nomor rekening, data keuangan, data
                            transaksi, IP Address, data teknis perangkat maupun sistem yang Anda gunakan, data profil, data
                            lokasi, informasi komunikasi Anda dengan Kami, dan informasi lainnya yang bersifat pribadi.</p>
                        <p>Data Pribadi Anda dapat kami peroleh dan kumpulkan melalui mekanisme sebagai berikut: [...]</p>
                    </div>
                </div>

                <!-- Bagian 3 -->
                <div class="border border-gray-200 rounded-lg">
                    <button onclick="toggleSection('sectionDiberikan')"
                        class="w-full text-left text-lg font-semibold bg-gray-100 px-4 py-3 rounded-t-lg">
                        Data Pribadi yang diberikan
                    </button>
                    <div id="sectionDiberikan" class="hidden p-4 text-sm space-y-2">
                        <p>Data Pribadi Anda yang diberikan secara langsung, termasuk namun tidak terbatas pada:</p>
                        <ul class="list-disc pl-6 space-y-1">
                            <li>Pendaftaran Akun atau pengisian formulir digital dalam penggunaan Situs mySatelite.</li>
                            <li>Pembuatan perjanjian dengan Perusahaan, pemesanan, penawaran, dan persetujuan Pembelian,
                                termasuk dokumen-dokumen lainnya yang diberikan kepada Perusahaan dan/atau melalui Situs
                                mySatelite.</li>
                            <li>Memberikan Data Pribadi pada saat mengakses dan/atau menggunakan layanan dari pihak ketiga
                                yang telah bekerja sama secara resmi dengan Perusahaan.</li>
                            <li>Memberikan informasi tambahan kepada Perusahaan dalam bentuk foto, tulisan, video,
                                pengiriman produk, pengiriman pertanyaan, komen atau tanggapan lainnya mengenai Situs
                                mySatelite.</li>
                            <li>Memberikan tanggapan, komentar, dan/atau feedback melalui e-mail, formulir, surat, panggilan
                                telepon atau fitur/aplikasi diskusi lainnya.</li>
                            <li>Mengisi data pada layanan Situs mySatelite untuk proses konfirmasi termasuk dan tidak
                                terbatas pada proses pembayaran yang meliputi data rekening bank, kartu debit, kartu kredit,
                                virtual account, dompet digital, internet banking, dan jasa pembayaran lainnya.</li>
                        </ul>
                    </div>
                </div>

                <!-- Bagian 4 -->
                <div class="border border-gray-200 rounded-lg">
                    <button onclick="toggleSection('sectionOtomatis')"
                        class="w-full text-left text-lg font-semibold bg-gray-100 px-4 py-3 rounded-t-lg">
                        Data Pribadi yang terkoleksi secara otomatis
                    </button>
                    <div id="sectionOtomatis" class="hidden p-4 text-sm space-y-2">
                        <p>Data Pribadi Anda yang Kami peroleh atau dapatkan secara otomatis, yang juga dapat melalui pihak
                            ketiga lainnya, termasuk namun tidak terbatas pada:</p>
                        <ul class="list-disc pl-6 space-y-1">
                            <li>Akses maupun interaksi melalui media sosial berkaitan dengan Situs mySatelite termasuk
                                namun tidak terbatas kepada Facebook, Youtube, Instagram, X, dan/atau media sosial lainnya
                                yang dapat mengumpulkan Data Pribadi Anda.</li>
                            <li>Kunjungan pada Situs mySatelite, yang dapat memberikan informasi dan data teknis seperti IP
                                Address, Mac Address, cookies dan informasi teknis lainnya yang memuat atau berhubungan
                                dengan Data Pribadi Anda yang bisa tercatat melalui sistem pencatatan (logging system).</li>
                            <li>Informasi lokasi Anda yang terhubung dengan layanan Kami melalui Situs mySatelite maupun
                                fitur perangkat atau aplikasi lainnya (seperti maps, dan sebagainya). Lokasi ini tidak
                                terbatas kepada alamat tempat tinggal yang berdasarkan wilayah, kota, provinsi namun juga
                                dapat berdasarkan koordinat lokasi.</li>
                        </ul>
                        <p>Anda menyatakan dan memastikan dari waktu ke waktu bahwa segala informasi dan Data Pribadi
                            diberikan dan/atau Kami peroleh adalah benar dan sah, serta telah mendapat persetujuan dari
                            pihak manapun yang berhak atau berwenang terhadap Data Pribadi Anda. Segala risiko dan
                            konsekuensi atas ketidakbenaran dan ketidaksahan Data Pribadi Anda maupun pelanggaran dan/atau
                            kelalaian Anda terhadap Kebijakan Privasi ini merupakan tanggung jawab Anda sepenuhnya.</p>
                    </div>
                </div>

            </div>
            <div class="mt-4 flex justify-end gap-2">
                <button id="disagreeButton" class="px-4 py-2 rounded bg-gray-300 text-sm">Batal</button>
                <button id="agreeButton" class="px-4 py-2 rounded bg-purple-500 text-white text-sm">Saya
                    Setuju</button>
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
                <button id="saveLocationButton" type="button"
                    class="bg-blue-600 text-white px-4 py-2 rounded flex items-center justify-center gap-2 transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span class="button-text">Gunakan Lokasi</span>
                    <svg class="spinner hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z">
                        </path>
                    </svg>
                </button>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.Laravel = {
                auth_token: "{{ Session::get('auth_token') }}",
                perangkat_id: "{{ $layanan->perangkat->encrypted_id }}",
                status_perusahaan: "{{ $user->status_perusahaan }}",
                latitude: "{{ $address->latitude }}",
                longitude: "{{ $address->longitude }}",
            };

            function openModal() {
                document.getElementById('termsModal').classList.remove('hidden');
                document.getElementById('termsModal').classList.add('flex');
            }

            function closeModal() {
                document.getElementById('termsModal').classList.remove('flex');
                document.getElementById('termsModal').classList.add('hidden');
            }

            // function agreeTerms() {
            //     document.getElementById('terms').checked = true;
            //     document.getElementById('terms').disabled = false;
            //     closeModal();
            // }

            function toggleSection(id) {
                const section = document.getElementById(id);
                section.classList.toggle('hidden');
            }
        </script>
        <script src="{{ asset('assets/js/order/address.js') }}"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAZJEfXn4JomPN0kP0TIlqL1Qr8AniNKIY&libraries=places">
        </script>
    @endpush
@endsection
