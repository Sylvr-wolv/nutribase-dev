@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-[#FAFCFB]">

    @include('layouts.sidebar')

    <main class="flex-1 flex flex-col min-w-0 lg:pl-72">

        {{-- Mobile Top Navbar --}}
        <header class="lg:hidden flex items-center justify-between bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-20 shadow-sm">
            <button @click="sidebarOpen = true" class="text-[#4E6F5C] hover:text-[#06B13D] transition">
                <i class="bi bi-list text-2xl"></i>
            </button>
            <div class="flex items-center gap-2">
                <img src="{{ asset('favicon.ico') }}" class="w-7 h-7 object-contain" alt="Logo">
                <span class="font-bold text-[#06B13D] text-sm">PHRI SUBANG</span>
            </div>
            <div class="w-8"></div>
        </header>

        <div class="flex-1 p-4 sm:p-6 lg:p-8 max-w-2xl mx-auto w-full">

            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-xl sm:text-2xl font-bold text-[#4E6F5C]">Profil Saya</h1>
                <p class="text-gray-500 text-sm">Kelola informasi akun Anda</p>
            </div>

            {{-- Profile Card --}}
            <div class="bg-white rounded-3xl shadow p-6 mb-4 border border-gray-100">

                {{-- Avatar + Info --}}
                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
                    <div class="w-16 h-16 rounded-2xl bg-[#D7F487] flex items-center justify-center flex-shrink-0">
                        <span class="text-2xl font-bold text-[#4E6F5C]">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 text-lg">{{ $user->name }}</p>
                        <span class="inline-block mt-1 text-xs font-semibold px-2.5 py-1 rounded-full
                            @php
                                echo match($user->role) {
                                    'kader' => 'bg-[#D7F487] text-[#4E6F5C]',
                                    'koordinator' => 'bg-[#79C80E]/20 text-[#4E6F5C]',
                                    'penerima' => 'bg-[#06B13D]/20 text-[#06B13D]',
                                    default   => 'bg-gray-100 text-gray-600',
                                };
                            @endphp">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>

                {{-- Read-only --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Username</p>
                        <p class="text-sm font-medium text-gray-700 bg-gray-50 rounded-xl px-3 py-2.5">{{ $user->username }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Role</p>
                        <p class="text-sm font-medium text-gray-700 bg-gray-50 rounded-xl px-3 py-2.5 capitalize">{{ $user->role }}</p>
                    </div>
                </div>

                {{-- Success --}}
                @if (session('success'))
                    <div class="mb-4 rounded-xl border border-[#79C80E]/40 bg-[#D7F487]/30 px-4 py-3 text-sm text-[#4E6F5C]">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- FORM --}}
                <form action="{{ route('profile.update') }}" method="POST"
                      x-data="{
                          showCurrentPassword: false,
                          showNewPassword: false,
                          showConfirmPassword: false,
                          newPassword: '',
                          get passwordStrength() {
                              if (this.newPassword.length === 0) return 0;
                              if (this.newPassword.length < 6)  return 1;
                              if (this.newPassword.length < 10) return 2;
                              return 3;
                          },
                          get strengthLabel() {
                              return ['', 'Lemah', 'Sedang', 'Kuat'][this.passwordStrength];
                          },
                          get strengthColor() {
                              return ['', 'bg-red-400', 'bg-yellow-400', 'bg-[#06B13D]'][this.passwordStrength];
                          },
                      }">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div class="mb-5">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400 block mb-1">
                            Name
                        </label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            class="w-full bg-gray-100 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#79C80E]/50 transition @error('name') ring-2 ring-red-400 @enderror"
                            required>
                    </div>

                    {{-- Username + Email --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                        <input type="text" name="username"
                            value="{{ old('username', $user->username) }}"
                            class="w-full bg-gray-100 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-[#79C80E]/50">
                    </div>

                    {{-- Divider --}}
                    <div class="flex items-center gap-3 my-6">
                        <div class="flex-1 h-px bg-gray-200"></div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Ganti Password</p>
                        <div class="flex-1 h-px bg-gray-200"></div>
                    </div>

                    {{-- Password --}}
                    <div class="mb-6">
                        <input type="password" name="new_password"
                            x-model="newPassword"
                            placeholder="Password baru..."
                            class="w-full bg-gray-100 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-[#79C80E]/50">
                        
                        <div x-show="newPassword.length > 0" class="mt-2">
                            <div class="flex gap-1">
                                <div class="h-1 flex-1 rounded-full" :class="passwordStrength >= 1 ? strengthColor : 'bg-gray-200'"></div>
                                <div class="h-1 flex-1 rounded-full" :class="passwordStrength >= 2 ? strengthColor : 'bg-gray-200'"></div>
                                <div class="h-1 flex-1 rounded-full" :class="passwordStrength >= 3 ? strengthColor : 'bg-gray-200'"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="w-full bg-[#06B13D] hover:bg-[#079c36] active:scale-95 text-white font-semibold py-3 rounded-xl transition-all duration-150 text-sm shadow-md shadow-[#06B13D]/30">
                        Simpan Perubahan
                    </button>

                </form>

                {{-- Logout (must submit POST /logout; button was type=button with no handler) --}}
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <form id="profile-logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                    </form>
                    <button
                        type="submit"
                        form="profile-logout-form"
                        class="w-full flex items-center justify-center gap-2 text-red-500 hover:text-red-600 bg-red-50 hover:bg-red-100 font-semibold py-3 rounded-xl transition text-sm">
                        <i class="bi bi-box-arrow-right"></i>
                        Keluar
                    </button>
                </div>

            </div>

        </div>
    </main>
</div>
@endsection