<nav class="bg-white">
    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
        <div class="relative flex h-16 items-center justify-between">
            <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                <button type="button"
                    class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-[#ED4436] hover:text-white focus:ring-2 focus:ring-white"
                    aria-controls="mobile-menu" aria-expanded="false">
                    <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>

            <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                <div class="flex shrink-0 items-center">
                    <img class="h-8 w-auto" src="{{ asset('assets/images/logo.png') }}" alt="Your Company">
                </div>
                <div class="hidden sm:ml-6 sm:block">
                    <div class="flex space-x-4">
                        <a href="{{ url('/') }}"
                            class="rounded-md px-3 py-2 text-sm font-medium 
                {{ request()->is('/') ? 'bg-[#ED0226] text-white' : 'text-black hover:bg-[#ED4436] hover:text-white' }}">
                            Beranda
                        </a>
                        <a href="{{ url('/products') }}"
                            class="rounded-md px-3 py-2 text-sm font-medium 
                {{ request()->is(['products', 'detail_product']) ? 'bg-[#ED0226] text-white' : 'text-black hover:bg-[#ED4436] hover:text-white' }}">
                            Layanan
                        </a>
                        <a href="{{ url('/promo') }}"
                            class="rounded-md px-3 py-2 text-sm font-medium 
                {{ request()->is('promo') ? 'bg-[#ED0226] text-white' : 'text-black hover:bg-[#ED4436] hover:text-white' }}">
                            Promo
                        </a>
                        <a href="{{ url('/faq') }}"
                            class="rounded-md px-3 py-2 text-sm font-medium 
                {{ request()->is('faq') ? 'bg-[#ED0226] text-white' : 'text-black hover:bg-[#ED4436] hover:text-white' }}">
                            FAQ
                        </a>
                    </div>
                </div>

            </div>

            <!-- Right side: Profile or Login -->
            <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                @auth
                    <!-- Notification -->
                    <button type="button"
                        class="relative rounded-full p-1 text-gray-400 hover:text-white focus:ring-2 focus:ring-white">
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                    </button>

                    <!-- Profile dropdown -->
                    <div class="relative ml-3">
                        <button type="button" class="relative flex rounded-full bg-gray-800 text-sm" id="user-menu-button">
                            <img class="size-8 rounded-full"
                                src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                alt="">
                        </button>

                        <div id="profile-dropdown"
                            class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black/5"
                            role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                            <a href="/user/dashboard" class="block px-4 py-2 text-sm text-gray-700"
                                role="menuitem">Dashboard</a>
                            <a href="/user/pesanan" class="block px-4 py-2 text-sm text-gray-700"
                                role="menuitem">Pesanan</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700">Sign
                                    out</button>
                            </form>
                        </div>
                    </div>
                @endauth

                @guest
                    <div class="flex gap-2 items-center">
                        <a href="{{ route('register') }}"
                            class="border border-[#ED0226] text-[#ED0226] px-4 py-2 rounded-md text-sm font-medium hover:bg-[#ED4436] hover:text-white">Register</a>
                        <a href="{{ route('login') }}"
                            class="bg-[#ED0226] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-[#ED4436]">Login</a>
                    </div>
                @endguest
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="sm:hidden hidden" id="mobile-menu">
        <div class="space-y-1 px-2 pt-2 pb-3">
            <a href="{{ url('/') }}"
                class="block rounded-md bg-gray-900 px-3 py-2 text-base font-medium text-white">Beranda</a>
            <a href="{{ url('/layanan') }}"
                class="block rounded-md px-3 py-2 text-base font-medium text-black hover:bg-[#ED4436] hover:text-white">Layanan</a>
            <a href="{{ url('/promo') }}"
                class="block rounded-md px-3 py-2 text-base font-medium text-black hover:bg-[#ED4436] hover:text-white">Promo</a>
            <a href="{{ url('/faq') }}"
                class="block rounded-md px-3 py-2 text-base font-medium text-black hover:bg-[#ED4436] hover:text-white">FAQ</a>
        </div>
    </div>
</nav>

<!-- Dropdown toggle script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userMenuButton = document.getElementById('user-menu-button');
        const dropdown = document.getElementById('profile-dropdown');
        const mobileMenuButton = document.querySelector('button[aria-controls="mobile-menu"]');
        const mobileMenu = document.getElementById('mobile-menu');

        document.addEventListener('click', function(e) {
            const isInsideProfile = userMenuButton?.contains(e.target) || dropdown?.contains(e.target);
            const isInsideMobileMenu = mobileMenu?.contains(e.target);

            // Toggle dropdown profile saat klik tombol profile
            if (userMenuButton?.contains(e.target)) {
                dropdown?.classList.toggle('hidden');
            } else if (!isInsideProfile) {
                // Klik di luar dropdown profile tutup dropdown
                dropdown?.classList.add('hidden');
            }

            // Toggle mobile menu saat klik hamburger button
            if (mobileMenuButton?.contains(e.target)) {
                mobileMenu.classList.toggle('hidden');
            } else if (!isInsideMobileMenu && !mobileMenuButton.contains(e.target)) {
                // Klik di luar mobile menu tutup mobile menu
                mobileMenu.classList.add('hidden');
            }
        });
    });
</script>
