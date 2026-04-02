@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col bg-[#FAFCFB]">
    <header class="border-b border-gray-100 bg-white/80 backdrop-blur-sm sticky top-0 z-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#06B13D] to-[#79C80E] flex items-center justify-center shadow-md shadow-[#06B13D]/25">
                    <i class="bi bi-heart-pulse-fill text-white text-lg"></i>
                </div>
                <div>
                    <p class="font-bold text-[#4E6F5C] leading-tight">NutriBase</p>
                    <p class="text-[10px] text-gray-400 uppercase tracking-wider">MBG Platform</p>
                </div>
            </div>
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-[#06B13D] hover:bg-[#079c36] text-white text-sm font-semibold px-5 py-2.5 shadow-md shadow-[#06B13D]/30 transition">
                <i class="bi bi-box-arrow-in-right"></i>
                Masuk
            </a>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center px-4 sm:px-6 py-12 sm:py-16">
        <div class="max-w-2xl w-full text-center">
            <h1 class="text-3xl sm:text-4xl font-bold text-[#4E6F5C] mb-4 leading-tight">
                Pemantauan gizi & distribusi bantuan, dalam satu tempat
            </h1>
            <p class="text-gray-600 text-sm sm:text-base mb-10 max-w-lg mx-auto leading-relaxed">
                NutriBase membantu kader dan koordinator mengelola data penerima, menu, jadwal, dan distribusi dengan lebih terstruktur.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4">
                <a href="{{ route('login') }}"
                   class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-2xl bg-gradient-to-r from-[#06B13D] to-[#79C80E] hover:from-[#05a637] hover:to-[#6fb90d] text-white font-semibold px-8 py-3.5 shadow-lg shadow-[#06B13D]/35 transition">
                    Masuk ke akun
                </a>
                <p class="text-xs text-gray-400 sm:max-w-[200px]">
                    Belum punya akses? Hubungi administrator untuk pembuatan akun.
                </p>
            </div>
        </div>
    </main>

    <footer class="border-t border-gray-100 py-6 text-center text-xs text-gray-400">
        &copy; {{ date('Y') }} {{ config('app.name', 'NutriBase') }}
    </footer>
</div>
@endsection
