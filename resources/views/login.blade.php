{{-- LOGIN PAGE (MongoDB Style Inspired) --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#001E2B] relative overflow-hidden">

    {{-- Gradient Glow --}}
    <div class="absolute w-[500px] h-[500px] bg-green-500/20 blur-[120px] rounded-full top-[-100px] left-[-100px]"></div>

    <div class="w-full max-w-md px-6 relative z-10">
        <div class="bg-[#0B2A3C] border border-white/10 rounded-2xl p-8 shadow-2xl">

            <h1 class="text-white text-2xl font-semibold mb-2">Selamat Datang</h1>
            <p class="text-gray-400 text-sm mb-6">Masuk ke akun anda</p>

            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500 text-red-400 text-sm p-3 rounded mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="text-sm text-gray-300">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required
                        class="w-full mt-1 px-4 py-3 bg-[#001E2B] border border-white/10 rounded-lg text-white focus:ring-2 focus:ring-green-500 outline-none">
                </div>

                <div>
                    <label class="text-sm text-gray-300">Password</label>
                    <input type="password" name="password" required
                        class="w-full mt-1 px-4 py-3 bg-[#001E2B] border border-white/10 rounded-lg text-white focus:ring-2 focus:ring-green-500 outline-none">
                </div>

                <button type="submit"
                    class="w-full py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition">
                    Masuk
                </button>
            </form>

            <p class="text-sm text-gray-400 mt-6 text-center">
                Belum memiliki akun?
                <a href="{{ route('register') }}" class="text-green-400 hover:underline">Daftar</a>
            </p>
        </div>
    </div>
</div>
@endsection
