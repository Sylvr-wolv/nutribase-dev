@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-[#4E6F5C]">

    <img src="{{ asset('images/bg_page_login.png') }}" 
         alt="Background" 
         class="absolute inset-0 w-full h-full object-cover opacity-30">

    <div class="absolute inset-0 bg-gradient-to-br from-[#4E6F5C]/95 via-[#06B13D]/70 to-[#4E6F5C]/95"></div>

    <div class="relative z-10 w-full max-w-md px-4">
        
        <div class="bg-[#FAFCFB]/95 backdrop-blur-md rounded-3xl shadow-[0_35px_60px_-15px_rgba(0,0,0,0.5)] border border-white/40 px-10 pt-16 pb-10 relative">

            {{-- Logo --}}
            <div class="absolute -top-12 left-1/2 -translate-x-1/2 w-24 h-24 bg-[#FAFCFB] rounded-full shadow-lg flex items-center justify-center p-4 ring-4 ring-[#D7F487]">
                <img src="{{ asset('favicon.ico') }}" alt="Logo" class="w-full h-full object-contain">
            </div>

            <h2 class="text-center text-[#4E6F5C] text-lg font-medium mt-6 mb-8">
                Silahkan masuk untuk melanjutkan
            </h2>

            {{-- Error --}}
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-600 text-sm p-3 rounded-r mb-4 shadow-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Success --}}
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-600 text-sm p-3 rounded-r mb-4 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                {{-- Username --}}
                <div class="mb-5 group">
                    <label class="block text-sm font-medium text-[#4E6F5C] mb-2 group-focus-within:text-[#06B13D] transition">
                        Username
                    </label>
                    <input 
                        type="text" 
                        name="username"
                        value="{{ old('username') }}"
                        required
                        autofocus
                        placeholder="Masukan Username Anda.."
                        class="w-full px-4 py-3 rounded-xl bg-[#FAFCFB] border border-gray-200 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#79C80E]/50 focus:border-[#79C80E] transition shadow-sm"
                    >
                </div>

                {{-- Password --}}
                <div class="mb-6 group">
                    <label class="block text-sm font-medium text-[#4E6F5C] mb-2 group-focus-within:text-[#06B13D] transition">
                        Password
                    </label>
                    <input 
                        type="password"
                        name="password"
                        required
                        placeholder="Masukan Password Anda.."
                        class="w-full px-4 py-3 rounded-xl bg-[#FAFCFB] border border-gray-200 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#79C80E]/50 focus:border-[#79C80E] transition shadow-sm"
                    >
                </div>

                {{-- Button --}}
                <button 
                    type="submit"
                    class="w-full py-3.5 rounded-xl text-white font-bold tracking-wide bg-gradient-to-r from-[#06B13D] to-[#79C80E] hover:from-[#05a637] hover:to-[#6fb90d] hover:scale-[1.01] active:scale-[0.98] transition-all duration-200 shadow-lg shadow-[#06B13D]/40"
                >
                    Masuk
                </button>
            </form>
        </div>
        
    </div>
</div>
@endsection