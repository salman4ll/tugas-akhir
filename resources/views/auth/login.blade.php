@extends('layouts.blank')

@section('title', 'Login')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-cover bg-center"
        style="background-image: url('/assets/images/bg-login.png');">
        <div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-sm">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="h-auto w-48 mx-auto mb-5">
            <h1 class="text-2xl font-extrabold text-[#001A41] text-center mb-6">Masuk ke MySatellite</h1>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-extrabold text-[#4E5764]">Username</label>
                    <input type="text" name="username" placeholder="Username" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-extrabold text-[#4E5764]">Password</label>
                    <input type="password" name="password" placeholder="Password" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="w-full flex justify-end">
                    <button>Lupa Kata Sandi?</button>
                </div>

                <div>
                    <button type="submit"
                        class="w-full bg-[#ED0226] text-white py-2 rounded hover:bg-[#a02b3d] transition">Login</button>
                </div>
            </form>

            <p class="text-center text-sm mt-4">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-[#001A41] hover:underline font-extrabold">Daftar</a>
            </p>
        </div>
    </div>
@endsection
