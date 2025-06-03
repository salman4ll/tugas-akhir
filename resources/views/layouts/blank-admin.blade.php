<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer>
        function toggleSubmenu(id) {
            document.getElementById(id).classList.toggle('hidden');
        }
    </script>
</head>

<body class="bg-gray-100 flex">

    {{-- Sidebar --}}
    @include('components.sidebar')

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col">
        {{-- Header --}}
        <header class="bg-white shadow px-6 py-4">
            <div class="text-xl font-semibold">Admin Panel</div>
        </header>

        {{-- Content --}}
        <main class="p-6">
            @yield('content')
        </main>
    </div>

</body>

</html>
