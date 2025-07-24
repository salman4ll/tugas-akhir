<div class="">
    <h2 class="font-bold text-lg mb-4">Informasi Perusahaan</h2>
    <div class="grid gap-4">
        <div class="flex flex-col gap-2">
            <label for="nama_perusahaan" class="text-[#4E5764] font-extrabold">Nama Perusahaan*</label>
            <input type="text" class="input border p-2 rounded-lg w-full" placeholder="Nama Perusahaan"
                id="nama_perusahaan" name="nama_perusahaan">
        </div>
        <div class="grid grid-cols-2 gap-6">
            <div class="flex flex-col gap-2">
                <label for="npwp_perusahaan" class="text-[#4E5764] font-extrabold">NPWP Perusahaan*</label>
                <input type="text" class="input border p-2 rounded-lg w-full" placeholder="NPWP Perusahaan"
                    id="npwp_perusahaan" name="npwp_perusahaan" maxlength="24" inputmode="numeric" autocomplete="off">
                <p id="npwp-error" class="text-red-500 text-sm hidden"></p>
            </div>
            <div class="flex flex-col gap-2">
                <label for="no_telp_perusahaan" class="text-[#4E5764] font-extrabold">Nomor Telpon Perusahaan*</label>
                <input type="text" class="input border p-2 rounded-lg w-full" placeholder="Nomor Telpon Perusahaan"
                    id="no_telp_perusahaan" name="no_telp_perusahaan">
                <p id="telp-error" class="text-red-500 text-sm hidden"></p>
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <label for="status_perusahaan" class="text-[#4E5764] font-extrabold">Status Perusahaan*</label>
            <select id="status_perusahaan" name="status_perusahaan" class="input border p-2 rounded-lg w-full">
                <option value="" disabled selected>Pilih Status Perusahaan</option>
                <option value="1">Wajib Pungut (WAPU)</option>
                <option value="2">Non-Wajib Pungut (non-WAPU) tanpa potong PPH 23</option>
                <option value="3">Non-Wajib Pungut (non-WAPU) potong PPH 23</option>
            </select>
            <p id="status-error" class="text-red-500 text-sm hidden"></p>
        </div>
        <div class="flex flex-col gap-2">
            <label for="username" class="text-[#4E5764] font-extrabold">Username*</label>
            <input type="text" class="input border p-2 rounded-lg w-full" placeholder="Cth: mysatellite"
                id="username" name="username">
            <p id="username-error" class="text-red-500 text-sm hidden"></p>
        </div>
        <div class="flex flex-col gap-2">
            <label for="email_perusahaan" class="text-[#4E5764] font-extrabold">Email Perusahaan*</label>
            <input type="email" class="input border p-2 rounded-lg w-full" placeholder="Cth. mysatellit@gmail.com"
                id="email_perusahaan" name="email_perusahaan">
            <p id="email-error" class="text-red-500 text-sm hidden"></p>
        </div>
        <div class="flex flex-col gap-2">
            <label for="password" class="text-[#4E5764] font-extrabold">Kata Sandi*</label>
            <input type="password" class="input border p-2 rounded-lg w-full" placeholder="Masukkan Kata Sandi"
                id="password" name="password">
        </div>
        <div class="flex flex-col gap-2">
            <label for="password_confirmation" class="text-[#4E5764] font-extrabold">Konfirmasi Kata Sandi*</label>
            <input type="password" class="input border p-2 rounded-lg w-full"
                placeholder="Masukkan Konfirmasi Kata Sandi" id="password_confirmation" name="password_confirmation">
            <p id="password-error" class="text-red-500 text-sm hidden"></p>
        </div>
    </div>
</div>
