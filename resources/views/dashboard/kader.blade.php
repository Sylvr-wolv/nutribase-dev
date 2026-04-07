@extends('layouts.app')

@section('content')
<div class="flex min-h-screen" style="background:#FAFCFB">

    @include('layouts.sidebar')

    <main class="flex-1 flex flex-col min-w-0 lg:pl-72">

        {{-- Mobile Navbar --}}
        <header class="lg:hidden flex items-center justify-between bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-20 shadow-sm">
            <button @click="sidebarOpen = true" style="color:#06B13D">
                <i class="bi bi-list text-2xl"></i>
            </button>
            <div class="flex items-center gap-2">
                <img src="{{ asset('favicon.ico') }}" class="w-7 h-7 object-contain" alt="Logo">
                <span class="font-bold text-sm" style="color:#06B13D">NutriBase</span>
            </div>
            <div class="w-8"></div>
        </header>

        <div class="flex-1 p-4 sm:p-6 lg:p-8">

            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-xl sm:text-2xl font-bold" style="color:#4E6F5C">
                    Selamat datang, {{ auth()->user()->name }} 👋
                </h1>
                <p class="text-gray-500 text-sm mt-1">Ringkasan data operasional hari ini</p>
            </div>

            {{-- ══ STAT CARDS ══ --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
                <div class="bg-white rounded-2xl shadow px-4 py-4 flex flex-col gap-2">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#D7F487">
                        <i class="bi bi-people-fill text-sm" style="color:#4E6F5C"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Total Penerima</p>
                        <p class="text-2xl font-black mt-0.5" style="color:#4E6F5C">{{ $totalPenerima }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow px-4 py-4 flex flex-col gap-2">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#D7F487">
                        <i class="bi bi-basket2-fill text-sm" style="color:#4E6F5C"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Total Stok</p>
                        <p class="text-2xl font-black mt-0.5" style="color:#06B13D">{{ number_format($totalStok) }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow px-4 py-4 flex flex-col gap-2">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#D7F487">
                        <i class="bi bi-truck text-sm" style="color:#4E6F5C"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Distribusi</p>
                        <p class="text-2xl font-black mt-0.5" style="color:#4E6F5C">{{ $totalDistribusi }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow px-4 py-4 flex flex-col gap-2">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-red-100">
                        <i class="bi bi-chat-square-text-fill text-sm text-red-500"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Ulasan Pending</p>
                        <p class="text-2xl font-black mt-0.5 text-red-500">{{ $feedbackPending }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">

                {{-- ══ DISTRIBUSI STATUS ══ --}}
                <div class="bg-white rounded-2xl shadow p-5">
                    <h2 class="text-sm font-bold mb-4" style="color:#4E6F5C">Status Distribusi</h2>
                    @php $total = max($totalDistribusi, 1); @endphp
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="font-medium text-gray-600">Diterima</span>
                                <span class="font-bold" style="color:#06B13D">{{ $diterima }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all" style="background:#06B13D;width:{{ round($diterima/$total*100) }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="font-medium text-gray-600">Pending</span>
                                <span class="font-bold text-yellow-600">{{ $pending }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="h-2 rounded-full bg-yellow-400 transition-all" style="width:{{ round($pending/$total*100) }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="font-medium text-gray-600">Gagal</span>
                                <span class="font-bold text-red-500">{{ $gagal }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="h-2 rounded-full bg-red-400 transition-all" style="width:{{ round($gagal/$total*100) }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Penerima per Kategori</p>
                        <div class="space-y-1.5">
                            @foreach(['ibu_hamil' => 'Ibu Hamil', 'ibu_menyusui' => 'Ibu Menyusui', 'balita' => 'Balita', 'lainnya' => 'Lainnya'] as $key => $label)
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500">{{ $label }}</span>
                                <span class="font-semibold" style="color:#4E6F5C">{{ $penerimaByKat[$key] ?? 0 }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ══ STOK MENU ══ --}}
                <div class="bg-white rounded-2xl shadow p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-bold" style="color:#4E6F5C">Stok Menu</h2>
                        @if($menuHabis > 0)
                        <span class="text-[10px] font-bold px-2 py-1 rounded-full bg-red-100 text-red-600">
                            {{ $menuHabis }} habis
                        </span>
                        @endif
                    </div>
                    <div class="space-y-3">
                        @forelse($menuStok as $m)
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 text-xs font-bold"
                                     style="background:#D7F487;color:#4E6F5C">
                                    {{ strtoupper(substr($m->nama, 0, 1)) }}
                                </div>
                                <span class="text-sm text-gray-700 truncate">{{ $m->nama }}</span>
                            </div>
                            <span class="text-sm font-bold flex-shrink-0 {{ $m->stok === 0 ? 'text-red-500' : '' }}"
                                  style="{{ $m->stok > 0 ? 'color:#06B13D' : '' }}">
                                {{ number_format($m->stok) }}
                            </span>
                        </div>
                        @empty
                        <p class="text-sm text-gray-400 text-center py-4">Belum ada menu</p>
                        @endforelse
                    </div>
                    <a href="{{ route('menu.index') }}"
                       class="mt-4 flex items-center justify-center gap-1.5 text-xs font-semibold py-2 rounded-xl transition"
                       style="background:#D7F487;color:#4E6F5C">
                        Kelola Menu & Stok <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                {{-- ══ JADWAL MENDATANG ══ --}}
                <div class="bg-white rounded-2xl shadow p-5">
                    <h2 class="text-sm font-bold mb-4" style="color:#4E6F5C">Jadwal Mendatang</h2>
                    <div class="space-y-3">
                        @forelse($upcomingJadwal as $j)
                        <div class="flex items-start gap-3 p-3 rounded-xl" style="background:#F0FDF4">
                            <div class="text-center flex-shrink-0">
                                <p class="text-lg font-black leading-none" style="color:#06B13D">{{ $j->tanggal->format('d') }}</p>
                                <p class="text-[10px] font-bold uppercase text-gray-400">{{ $j->tanggal->format('M') }}</p>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-700 truncate">{{ $j->menu->nama ?? '-' }}</p>
                                <p class="text-xs text-gray-400">{{ $j->rt }} · {{ $j->kader->name ?? '-' }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-6 text-gray-400 text-sm">
                            <i class="bi bi-calendar-check text-3xl block mb-2"></i>
                            Tidak ada jadwal mendatang
                        </div>
                        @endforelse
                    </div>
                    <a href="{{ route('jadwal.index') }}"
                       class="mt-4 flex items-center justify-center gap-1.5 text-xs font-semibold py-2 rounded-xl transition"
                       style="background:#D7F487;color:#4E6F5C">
                        Lihat Semua Jadwal <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            {{-- ══ RECENT DISTRIBUSI ══ --}}
            <div class="bg-white rounded-2xl shadow p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold" style="color:#4E6F5C">Distribusi Terbaru</h2>
                    <a href="{{ route('distribusi.index') }}" class="text-xs font-semibold" style="color:#06B13D">Lihat semua →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-gray-400 uppercase text-[10px] border-b">
                            <tr>
                                <th class="pb-3 pr-4">Penerima</th>
                                <th class="pb-3 pr-4">Menu</th>
                                <th class="pb-3 pr-4">Waktu</th>
                                <th class="pb-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($recentDistribusi as $d)
                            @php
                                $st = match($d->status) {
                                    'diterima' => 'background:#D7F487;color:#4E6F5C',
                                    'gagal'    => 'background:#FEE2E2;color:#991B1B',
                                    default    => 'background:#FEF9C3;color:#854D0E',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3 pr-4 font-medium text-gray-700">{{ $d->penerima->nama ?? '-' }}</td>
                                <td class="pr-4 text-xs">
                                    <span class="px-2 py-1 rounded-lg font-semibold" style="background:#D7F487;color:#4E6F5C">
                                        {{ $d->menu->nama ?? '-' }}
                                    </span>
                                </td>
                                <td class="pr-4 text-xs text-gray-400">{{ $d->waktu_distribusi->format('d M Y, H:i') }}</td>
                                <td>
                                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold" style="{{ $st }}">
                                        {{ strtoupper($d->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="py-8 text-center text-gray-400 text-sm">Belum ada distribusi</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
</div>
@endsection