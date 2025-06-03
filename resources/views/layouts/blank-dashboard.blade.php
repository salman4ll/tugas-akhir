<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laravel App')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css'])

    @stack('styles')
</head>

<body class="font-montserrat antialiased bg-[#F6F3F3]">
    <div class="min-h-screen">
        @include('components.navbar-dashboard')

        <div class="container mx-auto">
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

    @stack('scripts')
</body>

</html>
