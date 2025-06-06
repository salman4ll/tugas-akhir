@extends('layouts.blank')

@section('title', 'Product')

@section('content')
    <div class="bg-gray-100">
        <div class="mx-auto max-w-7xl px-8 py-10 min-h-screen text-gray-800">
            <div class="grid grid-cols-12 gap-8">
                <div class="md:col-span-6 col-span-12 flex justify-center">
                    <img id="main-image" src="{{ asset('assets/images/' . $product->image) }}"
                        alt="{{ $product->nama_produk }}" class="w-full h-auto max-h-[400px] object-contain">

                </div>

                <div class="md:col-span-6 col-span-12 flex flex-col gap-5">
                    <p class="font-bold text-3xl">{{ $product->nama_produk }}</p>
                    <p class="text-gray-700">{{ $product->deskripsi }}</p>

                    {{-- <div class="">
                        <button class="flex w-full text-left text-lg font-medium text-gray-800" onclick="toggleSpec(this)">
                            <span class="iconspec transition-transform duration-200">></span>
                            <span>Spesifikasi Produk</span>
                        </button>
                        <div
                            class="spec-content overflow-hidden transition-all duration-300 ease-in-out opacity-0 max-h-0 text-gray-600">
                            Memiliki kualitas yang terjamin dengan beberapa keunggulan:
                            <ul>
                                <li>- asjdasjsda</li>
                                <li>- asjdasjsda</li>
                                <li>- asjdasjsda</li>
                                <li>- asjdasjsda</li>
                                <li>- asjdasjsda</li>
                                <li>- asjdasjsda</li>
                            </ul>
                        </div>
                    </div> --}}

                    <div class="flex flex-col gap-4">
                        <p class="text-xl font-semibold">Pilih Perangkat:</p>
                        <div id="perangkat-options" class="gap-3 grid grid-cols-3">
                            @foreach ($product->perangkat as $perangkat)
                                <button
                                    class="perangkat-btn w-full py-2 bg-[#4E5764] text-white rounded-md transition active:scale-95 hover:bg-[#001A41] hover:border-[#7f74ff]"
                                    data-perangkat-id="{{ $perangkat->encrypted_id }}"
                                    data-image="{{ asset('assets/images/' . $perangkat->gambar_perangkat) }}">
                                    {{ $perangkat->nama_perangkat }}
                                    <span class="block text-sm text-gray-400">IDR
                                        {{ number_format($perangkat->harga_perangkat, 0, ',', '.') }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex flex-col gap-4">
                        <p class="text-xl font-semibold">Pilih Layanan:</p>
                        <div id="layanan-options" class="grid grid-cols-3 gap-3">
                            <!-- Layanan akan muncul di sini saat perangkat dipilih -->
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <button class="w-full py-3 bg-[#001A41] text-white rounded-md">Hubungi AM</button>
                        <button id="btn-bayar" disabled
                            class="w-full py-3 bg-[#ED0226] text-white rounded-md cursor-not-allowed">Lanjutkan
                            Pembayaran</button>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-8 mt-8">
                <div class="col-span-12">
                    <p class="font-bold text-3xl">Frequently Ask Question</p>
                </div>

                <div class="space-y-4" id="faq-section">
                    @foreach ($product->faq_produk as $faq)
                        <div class="border-b-[1px] border-black pb-3">
                            <button class="flex justify-between w-full text-left text-lg font-medium text-gray-800 py-3"
                                onclick="toggleFaq(this)">
                                <span>{{ $faq->pertanyaan }}</span>
                                <span class="icon transition-transform duration-200">+</span>
                            </button>
                            <div class="faq-content hidden text-sm text-gray-600 mt-2">
                                {{ $faq->jawaban }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        const produkData = @json($product->perangkat);

        const perangkatButtons = document.querySelectorAll('.perangkat-btn');
        const layananContainer = document.getElementById('layanan-options');
        const btnBayar = document.getElementById('btn-bayar');

        let selectedPerangkat = null;
        let selectedLayanan = null;

        perangkatButtons.forEach(button => {
            button.addEventListener('click', () => {
                selectedPerangkat = button.dataset.perangkatId;
                selectedLayanan = null;
                btnBayar.disabled = true;
                btnBayar.classList.add('bg-gray-400', 'cursor-not-allowed');
                btnBayar.classList.remove('bg-[#5a5964]', 'hover:bg-[#6d6677]', 'cursor-pointer');

                const newImage = button.getAttribute('data-image');
                document.getElementById('main-image').src = newImage;

                document.querySelectorAll('.perangkat-btn').forEach(btn => {
                    btn.classList.remove('ring-2', 'ring-[#7f74ff]', 'bg-[#001A41]');
                    btn.classList.add('bg-[#4E5764]');
                });
                button.classList.add('ring-2', 'ring-[#7f74ff]', 'bg-[#001A41]');
                button.classList.remove('bg-[#4E5764]');

                const layanan = produkData.find(p => p.encrypted_id == selectedPerangkat).layanan;

                layananContainer.innerHTML = '';
                layanan.forEach(item => {
                    const layananBtn = document.createElement('button');
                    layananBtn.className =
                        'layanan-btn w-full py-2 bg-[#4E5764] text-white rounded-md transition active:scale-95 hover:bg-[#001A41] hover:border-[#7f74ff]';
                    layananBtn.setAttribute('data-layanan-id', item.encrypted_id);
                    layananBtn.innerHTML =
                        `${item.deskripsi_layanan}<span class="block text-sm text-gray-400">${item.nama_layanan} - IDR ${new Intl.NumberFormat('id-ID').format(item.harga_layanan)}</span>`;

                    layananBtn.addEventListener('click', () => {
                        selectedLayanan = item.encrypted_id;
                        document.querySelectorAll('.layanan-btn').forEach(btn => {
                            btn.classList.remove('ring-2', 'ring-[#7f74ff]',
                                'bg-[#001A41]');
                            btn.classList.add('bg-[#4E5764]');
                        });
                        layananBtn.classList.add('ring-2', 'ring-[#7f74ff]',
                            'bg-[#001A41]');
                        layananBtn.classList.remove('bg-[#4E5764]');

                        if (selectedPerangkat && selectedLayanan) {
                            btnBayar.disabled = false;
                            btnBayar.classList.remove('bg-gray-400', 'cursor-not-allowed');
                            btnBayar.classList.add('bg-[#5a5964]', 'hover:bg-[#6d6677]',
                                'cursor-pointer');
                        }
                    });

                    layananContainer.appendChild(layananBtn);
                });
            });
        });

        btnBayar.addEventListener('click', () => {
            if (selectedPerangkat && selectedLayanan) {
                const url = `/payment-summary/${selectedLayanan}`;
                window.location.href = url;
            }
        });

        function toggleFaq(btn) {
            const content = btn.nextElementSibling;
            const icon = btn.querySelector('.icon');

            const isOpen = !content.classList.contains('hidden');

            document.querySelectorAll('.faq-content').forEach(c => c.classList.add('hidden'));
            document.querySelectorAll('.icon').forEach(i => i.innerText = '+');

            if (!isOpen) {
                content.classList.remove('hidden');
                icon.innerText = '−';
            }
        }

        function toggleSpec(btn) {
            const content = btn.nextElementSibling;
            const iconSpec = btn.querySelector('.iconspec');

            const isOpen = !content.classList.contains('hidden');

            document.querySelectorAll('.spec-content').forEach(c => {
                c.classList.add('hidden', 'opacity-0');
                c.classList.remove('max-h-96', 'opacity-100');
            });

            document.querySelectorAll('.iconspec').forEach(i => i.innerText = '>');

            if (!isOpen) {
                content.classList.remove('hidden');
                void content.offsetWidth;
                content.classList.remove('opacity-0');
                content.classList.add('max-h-96', 'opacity-100');
                iconSpec.innerText = 'v';
            }
        }
    </script>

@endsection
