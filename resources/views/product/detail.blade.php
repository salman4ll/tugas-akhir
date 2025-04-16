@extends('layouts.blank')

@section('title', 'Product')

@section('content')
<div class="bg-gray-100">
    <div class="mx-auto max-w-7xl px-8 py-10 min-h-screen text-gray-800">
        <div class="flex flex-col gap-8">
            <div class="grid grid-cols-12 gap-8">
                <div class="md:col-span-6 col-span-12 flex justify-center">
                    <img src="image/1.webp" alt="" class="w-full h-auto max-h-[400px] object-contain">
                </div>

                <div class="md:col-span-6 col-span-12 flex flex-col gap-5">
                    <div class="flex flex-row justify-between items-center">
                        <p class="font-bold text-3xl">Land Mobility</p>
                        <a href="" class="block text-blue-500 font-bold text-md">Bandingkan Produk ></a>
                    </div>

                    <div class="">
                        <button class="flex w-full text-left text-lg font-medium text-gray-800"
                            onclick="toggleContent(this, '.spec-content', '.iconspec', 'v', '>')">
                            <span class="iconspec transition-transform duration-200">></span>
                            <span>Spesifikasi Produk</span>
                        </button>
                        <div class="spec-content overflow-hidden transition-all duration-300 ease-in-out opacity-0 max-h-0 text-gray-600">
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
                    </div>

                    <div class="flex gap-2 flex-col">
                        <p class="text-xl">Pilih Perangkat: <span id="deviceId"></span></p>

                        <div class="flex flex-row gap-2" id="device-options">
                            <button
                                class="device-btn w-[200px] py-[5px] bg-[#242134] text-white rounded-md transition duration-200 ease-in-out active:scale-95 border-2 hover:bg-[#494366] hover:border-2 hover:border-[#7f74ff]" data-device-id="1">
                                Flat High Performance
                                <span class="block text-gray-400 text-sm">IDRXXXXX</span>
                            </button>

                            <button
                                class="device-btn w-[200px] py-[5px] bg-[#242134] text-white rounded-md transition duration-200 ease-in-out active:scale-95 border-2 hover:bg-[#494366] hover:border-2 hover:border-[#7f74ff]" data-device-id="2">
                                Standard Actuated
                                <span class="block text-gray-400 text-sm">IDRXXXXX</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex gap-2 flex-col">
                        <p class="text-xl">Pilih Perangkat: <span id="layananId"></span></p>

                        <div class="flex flex-row gap-2" id="layanan-options">
                            <button
                                class="layanan-btn w-[200px] py-[5px] bg-[#242134] text-white rounded-md transition duration-200 ease-in-out active:scale-95 border-2 hover:bg-[#494366] hover:border-2 hover:border-[#7f74ff]" data-layanan-id="1">
                                Mobile Priority
                                <span class="block text-gray-400 text-sm">50 GB - IDRXXXXX</span>
                            </button>

                            <button
                                class="layanan-btn w-[200px] py-[5px] bg-[#242134] text-white rounded-md transition duration-200 ease-in-out active:scale-95 border-2 hover:bg-[#494366] hover:border-2 hover:border-[#7f74ff]" data-layanan-id="2">
                                Mobile Prioritylayanan
                                <span class="block text-gray-400 text-sm">1 TB - IDRXXXXX</span>
                            </button>

                            <button
                                class="layanan-btn w-[200px] py-[5px] bg-[#242134] text-white rounded-md transition duration-200 ease-in-out active:scale-95 border-2 hover:bg-[#494366] hover:border-2 hover:border-[#7f74ff]" data-layanan-id="3">
                                Mobile Priority
                                <span class="block text-gray-400 text-sm">5 TB - IDRXXXXX</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-row gap-2">
                        <button class="w-[200px] py-[10px] bg-[#5a40ef] text-white rounded-md hover:">
                            Hubungi AM
                        </button>
                        
                        <button id="btn-bayar" disabled class="w-[200px] py-[10px] bg-gray-400 text-white rounded-md cursor-not-allowed">
                            Lanjutkan Pembayaran
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-8">
                <div class="col-span-12">
                    <p class="font-bold text-3xl">Frequently Ask Question</p>
                </div>

                <div class="col-span-12 space-y-4" id="faq-section"> 
                    <div class="border-b-[1px] border-black pb-3">
                        <button class="flex justify-between w-full text-left text-lg font-medium text-gray-800 py-3"
                            onclick="toggleContent(this, '.faq-content', '.icon', '−', '+')">
                            <span>Bagaimana cara membeli produk MyTelkomsat?</span>
                            <span class="icon transition-transform duration-200">+</span>
                        </button>
                        <div class="faq-content overflow-hidden transition-all duration-300 ease-in-out opacity-0 max-h-0">
                            Kamu bisa membeli produk MyTelkomsat melalui halaman produk. Pilih perangkat & layanan, lalu klik lanjutkan pembayaran.
                        </div>
                    </div>

                    <div class="border-b-[1px] border-black pb-3">
                        <button class="flex justify-between w-full text-left text-lg font-medium text-gray-800 py-3"
                            onclick="toggleContent(this, '.faq-content', '.icon', '−', '+')">
                            <span>Saya sudah melakukan registrasi, apa langkah selanjutnya?</span>
                            <span class="icon transition-transform duration-200">+</span>
                        </button>
                        <div class="faq-content overflow-hidden transition-all duration-300 ease-in-out opacity-0 max-h-0">
                            Silakan login ke akun kamu dan lengkapi data sebelum memilih produk dan layanan.
                        </div>
                    </div>

                    <div class="border-b-[1px] border-black pb-3">
                        <button class="flex justify-between w-full text-left text-lg font-medium text-gray-800 py-3"
                            onclick="toggleContent(this, '.faq-content', '.icon', '−', '+')">
                            <span>Dimana saya dapat melihat status pesanan?</span>
                            <span class="icon transition-transform duration-200">+</span>
                        </button>
                        <div class="faq-content overflow-hidden transition-all duration-300 ease-in-out opacity-0 max-h-0">
                            Status pesanan dapat dilihat di halaman profil kamu pada bagian "Riwayat Pemesanan".
                        </div>
                    </div>

                    <div class="border-b-[1px] border-black pb-3">
                        <button class="flex justify-between w-full text-left text-lg font-medium text-gray-800 py-3"
                            onclick="toggleContent(this, '.faq-content', '.icon', '−', '+')">
                            <span>Bagaimana cara saya memonitoring produk yang telah dibeli?</span>
                            <span class="icon transition-transform duration-200">+</span>
                        </button>
                        <div class="faq-content overflow-hidden transition-all duration-300 ease-in-out opacity-0 max-h-0">
                            Kamu bisa memonitoring produk lewat dashboard monitoring yang tersedia setelah login.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const buttonDevice = document.querySelectorAll('#device-options .device-btn');
    const deviceId = document.getElementById("deviceId");

    const buttonLayanan = document.querySelectorAll('#layanan-options .layanan-btn');
    const layananId = document.getElementById("layananId");

    const btnBayar = document.getElementById("btn-bayar");

    let selectedDevice = null;
    let selectedLayanan = null;

    function checkSelections() {
        if (selectedDevice && selectedLayanan) {
            btnBayar.removeAttribute('disabled');
            btnBayar.classList.remove('bg-gray-400', 'cursor-not-allowed');
            btnBayar.classList.add('bg-[#5a5964]', 'hover:bg-[#6d6677]', 'cursor-pointer');
        } else {
            btnBayar.setAttribute('disabled', true);
            btnBayar.classList.add('bg-gray-400', 'cursor-not-allowed');
            btnBayar.classList.remove('bg-[#5a5964]', 'hover:bg-[#6d6677]', 'cursor-pointer');
        }
    }

    buttonDevice.forEach(btn => {
        btn.addEventListener('click', () => {
            buttonDevice.forEach(b => b.classList.remove('bg-[#494366]', 'border-[#7f74ff]'));
            btn.classList.add('bg-[#494366]', 'border-2', 'border-[#7f74ff]');
            selectedDevice = btn.getAttribute('data-device-id');
            deviceId.innerHTML = selectedDevice;
            checkSelections();
        });
    });

    buttonLayanan.forEach(btn => {
        btn.addEventListener('click', () => {
            buttonLayanan.forEach(b => b.classList.remove('bg-[#494366]', 'border-[#7f74ff]'));
            btn.classList.add('bg-[#494366]', 'border-2', 'border-[#7f74ff]');
            selectedLayanan = btn.getAttribute('data-layanan-id');
            layananId.innerHTML = selectedLayanan;
            checkSelections();
        });
    });

    btnBayar.addEventListener('click', () => {
        if (selectedDevice && selectedLayanan) {
            localStorage.setItem('selectedDevice', selectedDevice);
            localStorage.setItem('selectedLayanan', selectedLayanan);

            window.location.href = "/payment_summary";
        } else {
            alert("Silakan pilih perangkat dan layanan terlebih dahulu.");
        }
    });

    function toggleContent(btn, contentSelector, iconSelector, openIcon = '−', closeIcon = '+') {
        const content = btn.nextElementSibling;
        const icon = btn.querySelector(iconSelector);
        const isOpen = content.style.maxHeight && content.style.maxHeight !== "0px";

        document.querySelectorAll(contentSelector).forEach(c => {
            c.style.maxHeight = "0px";
            c.classList.remove("opacity-100");
            c.classList.add("opacity-0");
        });

        document.querySelectorAll(iconSelector).forEach(i => i.innerText = closeIcon);

        if (!isOpen) {
            content.style.maxHeight = content.scrollHeight + "px";
            content.classList.remove("opacity-0");
            content.classList.add("opacity-100");
            icon.innerText = openIcon;
        }
    }
</script>
@endsection
