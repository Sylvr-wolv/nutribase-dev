@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#001E2B] relative overflow-hidden">

    {{-- Background Glows --}}
    <div class="absolute w-[600px] h-[600px] bg-green-500/10 blur-[130px] rounded-full top-[-150px] left-[-150px]"></div>
    <div class="absolute w-[500px] h-[500px] bg-emerald-500/10 blur-[120px] rounded-full bottom-[-120px] right-[-120px]"></div>

    <div class="w-full max-w-md px-6 relative z-10">
        <div class="bg-[#0B2A3C] border border-white/10 rounded-3xl p-8 shadow-2xl">

            <div class="text-center mb-8">
                <h1 class="text-white text-3xl font-semibold">Buat Akun</h1>
                <p class="text-gray-400 mt-2">Daftar untuk mengakses layanan MBG</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/50 text-red-400 text-sm p-4 rounded-2xl mb-6">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm text-gray-300 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-5 py-3.5 bg-[#001E2B] border border-white/10 rounded-2xl text-white placeholder-gray-500 focus:border-green-500 focus:ring-2 focus:ring-green-500/30 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm text-gray-300 mb-1">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required
                        class="w-full px-5 py-3.5 bg-[#001E2B] border border-white/10 rounded-2xl text-white placeholder-gray-500 focus:border-green-500 focus:ring-2 focus:ring-green-500/30 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm text-gray-300 mb-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-5 py-3.5 bg-[#001E2B] border border-white/10 rounded-2xl text-white placeholder-gray-500 focus:border-green-500 focus:ring-2 focus:ring-green-500/30 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm text-gray-300 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-5 py-3.5 bg-[#001E2B] border border-white/10 rounded-2xl text-white placeholder-gray-500 focus:border-green-500 focus:ring-2 focus:ring-green-500/30 outline-none transition">
                </div>

                <button type="submit"
                    class="w-full py-4 bg-green-500 hover:bg-green-600 active:bg-green-700 text-white font-semibold rounded-2xl transition mt-2">
                    Daftar Sekarang
                </button>
            </form>

            <p class="text-center text-sm text-gray-400 mt-8">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-green-400 hover:text-green-300 font-medium">Masuk</a>
            </p>
        </div>

    </div>
</div>
@endsection