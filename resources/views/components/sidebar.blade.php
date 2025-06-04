@php
    $role = auth('admin')->user()->role->nama;
@endphp

<aside class="w-64 bg-white h-screen shadow-md">
    <div class="p-4 font-bold text-xl border-b">MySatelite</div>
    <nav class="mt-4">
        <ul>
            {{-- Dashboard --}}
            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="block px-4 py-2 hover:bg-gray-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-200 font-bold' : '' }}">
                    Dashboard
                </a>
            </li>

            {{-- Jika role-nya adalah am --}}
            @if ($role === 'am')
                <li>
                    <button onclick="toggleSubmenu('submenu-pesanan')"
                        class="w-full text-left px-4 py-2 hover:bg-gray-200 {{ request()->is('pesanan/*') ? 'bg-gray-200 font-bold' : '' }}">
                        Customer
                    </button>
                    <ul id="submenu-pesanan" class="{{ request()->is('pesanan/*') ? '' : 'hidden' }} ml-4">
                        <li>
                            <a
                                class="block px-4 py-2 hover:bg-gray-100 {{ request()->routeIs('pesanan.semua') ? 'bg-gray-200 font-bold' : '' }}">
                                Data Customer
                            </a>
                        </li>
                        <li>
                            <a
                                class="block px-4 py-2 hover:bg-gray-100 {{ request()->routeIs('pesanan.ekspedisi') ? 'bg-gray-200 font-bold' : '' }}">
                                Order Management
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            {{-- Jika role-nya adalah logistik --}}
            @if ($role === 'logistik')
                {{-- Pesanan --}}
                <li>
                    <button onclick="toggleSubmenu('submenu-pesanan')"
                        class="w-full text-left px-4 py-2 hover:bg-gray-200 {{ request()->is('pesanan/*') ? 'bg-gray-200 font-bold' : '' }}">
                        Pesanan
                    </button>
                    <ul id="submenu-pesanan" class="{{ request()->is('admin/orders/*') ? '' : 'hidden' }} ml-4">
                        <li>
                            <a href="{{ route('admin.orders', ['type' => 'all']) }}"
                                class="block px-4 py-2 hover:bg-gray-100 {{ request()->is('admin/orders/all') ? 'bg-gray-200 font-bold' : '' }}">
                                Semua
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.orders', ['type' => 'ekspedisi']) }}"
                                class="block px-4 py-2 hover:bg-gray-100 {{ request()->is('admin/orders/ekspedisi') ? 'bg-gray-200 font-bold' : '' }}">
                                Ekspedisi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.orders', ['type' => 'ambil_ditempat']) }}"
                                class="block px-4 py-2 hover:bg-gray-100 {{ request()->is('admin/orders/ambil_ditempat') ? 'bg-gray-200 font-bold' : '' }}">
                                Ambil di Tempat
                            </a>
                        </li>
                    </ul>

                </li>
                <li>
                    <a href="{{ route('admin.get-metode-pengiriman') }}"
                        class="block px-4 py-2 hover:bg-gray-200 {{ request()->routeIs('admin.get-metode-pengiriman') ? 'bg-gray-200 font-bold' : '' }}">
                        Ekspedisi
                    </a>
                </li>
            @endif
            <li>
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="hidden">
                    @csrf
                </form>

                <a href="#" class="block px-4 py-2 hover:bg-gray-200"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
            </li>
        </ul>
    </nav>
</aside>
