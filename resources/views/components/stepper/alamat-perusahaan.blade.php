<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Form Fields -->
    <div class="space-y-4">
        <!-- Provinsi -->
        <div class="flex flex-col gap-2">
            <label for="provinsi_id" class="text-[#4E5764] font-extrabold">Provinsi*</label>
            <select id="provinsi_id" name="provinsi_id" class="input border p-2 rounded-lg w-full"
                data-placeholder="Pilih Provinsi">
                <option value="">Pilih Provinsi</option>
            </select>
        </div>

        <!-- Kabupaten/Kota -->
        <div class="flex flex-col gap-2">
            <label for="kabupaten_id" class="text-[#4E5764] font-extrabold">Kabupaten/Kota*</label>
            <select id="kabupaten_id" name="kabupaten_id" class="input border p-2 rounded-lg w-full" disabled
                data-placeholder="Pilih Kabupaten/Kota">
                <option value="">Pilih Kabupaten/Kota</option>
            </select>
        </div>

        <!-- Kecamatan -->
        <div class="flex flex-col gap-2">
            <label for="kecamatan_id" class="text-[#4E5764] font-extrabold">Kecamatan*</label>
            <select id="kecamatan_id" name="kecamatan_id" class="input border p-2 rounded-lg w-full" disabled
                data-placeholder="Pilih Kecamatan">
                <option value="">Pilih Kecamatan</option>
            </select>
        </div>

        <!-- Kelurahan/Desa -->
        <div class="flex flex-col gap-2">
            <label for="kelurahan_id" class="text-[#4E5764] font-extrabold">Kelurahan/Desa*</label>
            <select id="kelurahan_id" name="kelurahan_id" class="input border p-2 rounded-lg w-full" disabled
                data-placeholder="Pilih Kelurahan/Desa">
                <option value="">Pilih Kelurahan/Desa</option>
            </select>
        </div>

        <!-- RT/RW Row -->
        <div class="grid grid-cols-3 gap-4">
            <div class="flex flex-col gap-2">
                <label for="rt" class="text-[#4E5764] font-extrabold">RT*</label>
                <input type="text" id="rt" name="rt" placeholder="001"
                    class="input border p-2 rounded-lg w-full" maxlength="3">
                <small class="text-gray-500 text-xs">Format: 001, 002, dst.</small>
            </div>
            <div class="flex flex-col gap-2">
                <label for="rw" class="text-[#4E5764] font-extrabold">RW*</label>
                <input type="text" id="rw" name="rw" placeholder="001"
                    class="input border p-2 rounded-lg w-full" maxlength="3">
                <small class="text-gray-500 text-xs">Format: 001, 002, dst.</small>
            </div>
            <div class="flex flex-col gap-2">
                <label for="kode_pos" class="text-[#4E5764] font-extrabold">Kode Pos*</label>
                <input type="text" id="kode_pos" name="kode_pos" placeholder="16119"
                    class="input border p-2 rounded-lg w-full" maxlength="5">
                <small class="text-gray-500 text-xs">5 digit angka</small>
            </div>
        </div>

        <!-- Detail Alamat -->
        <div class="flex flex-col gap-2">
            <label for="detail_alamat" class="text-[#4E5764] font-extrabold">Detail Alamat*</label>
            <textarea id="detail_alamat" name="detail_alamat"
                placeholder="Masukkan alamat lengkap (nama jalan, nomor rumah, patokan, dll.)"
                class="input border p-2 rounded-lg w-full h-24 resize-none"></textarea>
            <small class="text-gray-500">Contoh: Jl. Merdeka No. 123, dekat Indomaret</small>
        </div>

        <!-- Koordinat (Hidden fields, will be filled by map) -->
        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">

        <!-- Coordinates Display -->
        <div class="bg-gray-50 p-3 rounded-lg">
            <p class="text-sm text-gray-600 font-semibold mb-1">Koordinat Lokasi:</p>
            <div class="flex gap-4 text-sm">
                <span>Latitude: <span id="lat-display" class="font-mono">-</span></span>
                <span>Longitude: <span id="lng-display" class="font-mono">-</span></span>
            </div>
            <p class="text-xs text-gray-500 mt-1">Klik pada peta untuk menandai lokasi perusahaan</p>
        </div>
    </div>

    <!-- Google Maps -->
    <div class="flex flex-col gap-2">
        <label class="text-[#4E5764] font-extrabold">Lokasi Perusahaan*</label>

        <!-- Search Box -->
        <div class="relative">
            <input type="text" id="address-search"
                placeholder="Cari alamat atau tempat (contoh: Jl. Sudirman Jakarta, Starbucks Bogor)"
                class="w-full p-3 border rounded-lg pr-10 focus:ring-2 focus:ring-[#CE0A45] focus:border-[#CE0A45]">
            <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="flex gap-2 flex-wrap">
            <button type="button" id="current-location-btn"
                class="flex items-center gap-2 px-3 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Lokasi Saya
            </button>
            <button type="button" id="clear-search-btn"
                class="flex items-center gap-2 px-3 py-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
                Bersihkan
            </button>
        </div>

        <!-- Map Container -->
        <div id="map" class="w-full h-96 border rounded-lg bg-gray-100 flex items-center justify-center">
            <div class="text-gray-500">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#CE0A45] mx-auto mb-2"></div>
                <p class="text-sm">Memuat peta...</p>
            </div>
        </div>
    </div>
</div>
