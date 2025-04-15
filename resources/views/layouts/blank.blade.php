<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laravel App')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css'])

    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen">
        @include('components.navbar')
        
        @yield('content')
    </div>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    
    @stack('scripts')
</body>
</html>
