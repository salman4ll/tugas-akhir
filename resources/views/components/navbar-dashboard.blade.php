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

            <button onclick="location.href='{{ url('/') }}'" class="flex items-center sm:mr-6">
                <img class="h-8 w-auto" src="{{ asset('assets/images/logo.png') }}" alt="Your Company">
            </button>

            <!-- Menu di tengah -->
            <div class="hidden sm:flex flex-1 justify-center">
                <div class="flex space-x-4">
                    <a href="{{ url('/user/dashboard') }}"
                        class="rounded-md px-3 py-2 text-sm font-medium 
            {{ request()->is('user/dashboard*') ? 'bg-[#ED0226] text-white' : 'text-black hover:bg-[#ED4436] hover:text-white' }}">
                        Dashboard
                    </a>
                    <a href="{{ url('/user/pesanan') }}"
                        class="rounded-md px-3 py-2 text-sm font-medium 
            {{ request()->is(['user/pesanan*', 'user/pesanan/detail/*']) ? 'bg-[#ED0226] text-white' : 'text-black hover:bg-[#ED4436] hover:text-white' }}">
                        Pesanan
                    </a>
                </div>
            </div>

            <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                @auth
                    <!-- Profile dropdown -->
                    <div class="relative ml-3">
                        <button type="button" class="relative flex rounded-full bg-gray-800 text-sm" id="user-menu-button">
                            <div
                                class="size-8 rounded-full bg-[#ED0226] text-white flex items-center justify-center text-sm font-semibold">
                                {{ strtoupper(substr(Auth::user()->nama_perusahaan, 0, 1)) }}
                            </div>
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
            <a href="{{ url('/user/dashboard') }}"
                class="block rounded-md px-3 py-2 text-base font-medium text-black hover:bg-[#ED4436] hover:text-white">Dashboard</a>
            <a href="{{ url('/user/pesanan') }}"
                class="block rounded-md px-3 py-2 text-base font-medium text-black hover:bg-[#ED4436] hover:text-white">Pesanan</a>
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
