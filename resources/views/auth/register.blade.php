@extends('layouts.blank')

@section('title', 'Register Page')

@section('content')
    <div class="bg-[#CE0A45] flex flex-col justify-center items-center text-white font-bold p-5 gap-4">
        <h1 class="text-xl">Daftar Akun</h1>
        <div class="flex gap-5 items-center" id="step-indicator">
            <div class="step flex items-center gap-3" data-step="1">
                <span
                    class="bg-white w-8 h-8 flex items-center justify-center rounded-full text-[#CE0A45] font-semibold">1</span>
                <span>Informasi Perusahaan</span>
            </div>
            <span>-</span>
            <div class="step opacity-50 flex items-center gap-3" data-step="2">
                <span
                    class="bg-white w-8 h-8 flex items-center justify-center rounded-full text-[#CE0A45] font-semibold">2</span>
                <span>Narahubung</span>
            </div>
            <span>-</span>
            <div class="step opacity-50 flex items-center gap-3" data-step="3">
                <span
                    class="bg-white w-8 h-8 flex items-center justify-center rounded-full text-[#CE0A45] font-semibold">3</span>
                <span>Alamat Perusahaan</span>
            </div>
        </div>
    </div>

    <form id="registerForm" method="POST" action="{{ route('register') }}"
        class="flex flex-col w-full justify-center items-center my-8">
        @csrf
        <div class="flex flex-col w-[50%] bg-white rounded-xl shadow p-6">
            <div id="step-content">
                <div id="step-0" class="step-pane">
                    @include('components.stepper.informasi-perusahaan')
                </div>
                <div id="step-1" class="step-pane hidden">
                    @include('components.stepper.narahubung')
                </div>
                <div id="step-2" class="step-pane hidden">
                    @include('components.stepper.alamat-perusahaan')
                </div>
            </div>

            <div class="flex flex-col items-center gap-4 justify-center w-full font-semibold mt-5">
                <button type="button" id="nextBtn" class="btn bg-[#ED0226] w-full text-white rounded-lg p-2"
                    disabled>Selanjutnya</button>
                <button type="submit" id="submitBtn" class="btn bg-[#ED0226] w-full text-white rounded-lg p-2 hidden"
                    disabled>Daftar Akun</button>
                <button type="button" id="prevBtn" class="btn text-[#ED0226] hidden p-2">Sebelumnya</button>
            </div>
        </div>
    </form>

    @push('scripts')
    <script src="{{ asset('assets/js/register/validation-informasi-perusahaan.js') }}"></script>
    <script src="{{ asset('assets/js/register/validation-narahubung.js') }}"></script>
    <script src="{{ asset('assets/js/register/validation-alamat-perusahaan.js') }}"></script>
        <script src="{{ asset('assets/js/register/index.js') }}"></script>
    @endpush
@endsection
