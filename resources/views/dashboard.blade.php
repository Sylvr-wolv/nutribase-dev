@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100">

    @include('layouts.sidebar')

    <main class="flex-1 flex flex-col min-w-0 lg:pl-72">

        {{-- Mobile Top Navbar --}}
        <header class="lg:hidden flex items-center justify-between bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-20 shadow-sm">
            <button @click="sidebarOpen = true" class="text-purple-900 hover:text-purple-600 transition">
                <i class="bi bi-list text-2xl"></i>
            </button>
            <div class="flex items-center gap-2">
                <img src="{{ asset('favicon.ico') }}" class="w-7 h-7 object-contain" alt="Logo">
                <span class="font-bold text-yellow-600 text-sm">PHRI SUBANG</span>
            </div>
            <div class="w-8"></div>
        </header>

        {{-- Centered content --}}
        <div class="flex-1 flex items-center justify-center p-6 sm:p-10">
            <div class="text-center max-w-md w-full">
                <img
                    src="https://cdn-icons-png.flaticon.com/512/7466/7466140.png"
                    alt="Error"
                    class="w-40 sm:w-64 mx-auto mb-6 sm:mb-8"
                >

                <h1 class="text-2xl sm:text-3xl font-bold text-purple-900 mb-3">
                    Terjadi Kesalahan
                </h1>

                <p class="text-gray-600 text-sm sm:text-base mb-8">
                    Data tidak dapat dimuat saat ini.<br>
                    Silakan coba beberapa saat lagi.
                </p>

                <div class="flex justify-center gap-4">
                    <a href="{{ url()->previous() }}"
                       class="px-5 py-2.5 sm:px-6 sm:py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm sm:text-base font-medium rounded-xl transition">
                        Kembali
                    </a>
                </div>
            </div>
        </div>

    </main>
</div>
@endsection