@extends('layouts.blank')

@section('title', 'Login')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-md">
            <h1 class="text-2xl font-bold text-center mb-6">Login</h1>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" placeholder="Email" required
                        class="w-full border border-gray-300 rounded px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" placeholder="Password" required
                        class="w-full border border-gray-300 rounded px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <button type="submit"
                        class="w-full bg-[#ED4436] text-white py-2 rounded hover:bg-blue-700 transition">Login</button>
                </div>
            </form>

            <p class="text-center text-sm mt-4">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Daftar di sini</a>
            </p>
        </div>
    </div>
@endsection
