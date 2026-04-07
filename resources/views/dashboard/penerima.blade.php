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

        <div class="flex-1 p-4 sm:p-6 lg:p-8 max-w-2xl mx-auto w-full">

            {{-- Greeting --}}
            <div class="mb-6">
                <h1 class="text-xl sm:text-2xl font-bold" style="color:#4E6F5C">
                    Halo, {{ $penerima->user->name }} 👋
                </h1>
                <p class="text-gray-500 text-sm mt-1">Status penerimaan bantuan makanan Anda</p>
            </div>

            {{-- ══ PROFILE CARD ══ --}}
            <div class="bg-white rounded-2xl shadow p-5 mb-4">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-xl font-black flex-shrink-0"
                         style="background:#4E6F5C;color:#D7F487">
                        {{ strtoupper(substr($penerima->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-800 text-base">{{ $penerima->user->name }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ $penerima->nik }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">RT {{ $penerima->rt }}</p>
                    </div>
                    @php
                        $katStyle = match($penerima->kategori) {
                            'ibu_hamil'    => 'background:#FEF3C7;color:#92400E',
                            'ibu_menyusui' => 'background:#DBEAFE;color:#1E40AF',
                            'balita'       => 'background:#D7F487;color:#3A5C0D',
                            default        => 'background:#F3F4F6;color:#4B5563',
                        };
                        $katLabel = match($penerima->kategori) {
                            'ibu_hamil'    => 'Ibu Hamil',
                            'ibu_menyusui' => 'Ibu Menyusui',
                            'balita'       => 'Balita',
                            default        => 'Lainnya',
                        };
                    @endphp
                    <span class="text-xs font-semibold px-3 py-1.5 rounded-full flex-shrink-0"
                          style="{{ $katStyle }}">{{ $katLabel }}</span>
                </div>

                {{-- Estimasi durasi countdown --}}
                @if($daysLeft > 0)
                <div class="flex items-center gap-3 p-3 rounded-xl" style="background:#F0FDF4;border:1px solid #D7F487">
                    <i class="bi bi-clock-history text-lg" style="color:#06B13D"></i>
                    <div>
                        <p class="text-xs font-bold" style="color:#4E6F5C">Program Aktif</p>
                        <p class="text-xs text-gray-500">
                            Estimasi selesai: <span class="font-semibold">{{ $penerima->estimasi_durasi->format('d M Y') }}</span>
                            ({{ $daysLeft }} hari lagi)
                        </p>
                    </div>
                </div>
                @else
                <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-200">
                    <i class="bi bi-check-circle-fill text-lg text-gray-400"></i>
                    <div>
                        <p class="text-xs font-bold text-gray-500">Program Selesai</p>
                        <p class="text-xs text-gray-400">{{ $penerima->estimasi_durasi->format('d M Y') }}</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- ══ PENERIMAAN STATS ══ --}}
            <div class="grid grid-cols-3 gap-3 mb-4">
                <div class="bg-white rounded-2xl shadow px-3 py-4 flex flex-col items-center gap-1 text-center">
                    <span class="text-2xl font-black" style="color:#4E6F5C">{{ $totalDistribusi }}</span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Total</span>
                </div>
                <div class="bg-white rounded-2xl shadow px-3 py-4 flex flex-col items-center gap-1 text-center">
                    <span class="text-2xl font-black" style="color:#06B13D">{{ $diterima }}</span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Diterima</span>
                </div>
                <div class="bg-white rounded-2xl shadow px-3 py-4 flex flex-col items-center gap-1 text-center">
                    <span class="text-2xl font-black text-yellow-500">{{ $pending }}</span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Pending</span>
                </div>
            </div>

            {{-- ══ RIWAYAT TERBARU ══ --}}
            <div class="bg-white rounded-2xl shadow p-5 mb-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold" style="color:#4E6F5C">Penerimaan Terbaru</h2>
                    <a href="{{ route('riwayat') }}" class="text-xs font-semibold" style="color:#06B13D">Semua →</a>
                </div>
                <div class="space-y-3">
                    @forelse($latestDistribusi as $d)
                    @php
                        $st = match($d->status) {
                            'diterima' => 'background:#D7F487;color:#4E6F5C',
                            'gagal'    => 'background:#FEE2E2;color:#991B1B',
                            default    => 'background:#FEF9C3;color:#854D0E',
                        };
                    @endphp
                    <div class="flex items-center gap-3 p-3 rounded-xl" style="background:#FAFCFB">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-700 truncate">{{ $d->menu->nama ?? '-' }}</p>
                            <p class="text-xs text-gray-400">
                                {{ $d->waktu_distribusi->format('d M Y, H:i') }}
                                · {{ $d->kader->name ?? '-' }}
                            </p>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full flex-shrink-0"
                              style="{{ $st }}">
                            {{ strtoupper($d->status) }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-6 text-gray-400 text-sm">
                        <i class="bi bi-box-seam text-3xl block mb-2"></i>
                        Belum ada riwayat penerimaan
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- ══ JADWAL MENDATANG ══ --}}
            @if($upcomingJadwal->isNotEmpty())
            <div class="bg-white rounded-2xl shadow p-5 mb-4">
                <h2 class="text-sm font-bold mb-4" style="color:#4E6F5C">Jadwal Mendatang</h2>
                <div class="space-y-3">
                    @foreach($upcomingJadwal as $j)
                    <div class="flex items-center gap-3 p-3 rounded-xl" style="background:#F0FDF4">
                        <div class="text-center w-10 flex-shrink-0">
                            <p class="text-lg font-black leading-none" style="color:#06B13D">{{ $j->tanggal->format('d') }}</p>
                            <p class="text-[10px] font-bold uppercase text-gray-400">{{ $j->tanggal->format('M') }}</p>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-700">{{ $j->menu->nama ?? '-' }}</p>
                            <p class="text-xs text-gray-400">RT {{ $j->rt }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ══ ULASAN SAYA ══ --}}
            <div class="bg-white rounded-2xl shadow p-5 mb-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-bold" style="color:#4E6F5C">Ulasan Saya</h2>
                    <a href="{{ route('feedback.index') }}" class="text-xs font-semibold" style="color:#06B13D">Lihat →</a>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-center">
                        <p class="text-3xl font-black" style="color:#06B13D">{{ $totalFeedback }}</p>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Ulasan</p>
                    </div>
                    @if($totalFeedback > 0)
                    <div class="flex-1 text-center">
                        <div class="flex items-center justify-center gap-1 mb-1">
                            @for($s = 1; $s <= 5; $s++)
                            <i class="bi bi-star-fill text-lg {{ $s <= round($avgRating) ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                            @endfor
                        </div>
                        <p class="text-xs text-gray-500">Rata-rata <span class="font-bold text-gray-700">{{ number_format($avgRating, 1) }}</span> dari 5</p>
                    </div>
                    @else
                    <div class="flex-1 text-center text-gray-400 text-sm">
                        Belum ada ulasan. <a href="{{ route('feedback.index') }}" class="font-semibold" style="color:#06B13D">Beri ulasan →</a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- ══ QUICK LINKS ══ --}}
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('riwayat') }}"
                   class="bg-white rounded-2xl shadow p-4 flex items-center gap-3 hover:shadow-md transition">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#D7F487">
                        <i class="bi bi-clock-history text-sm" style="color:#4E6F5C"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold" style="color:#4E6F5C">Riwayat</p>
                        <p class="text-xs text-gray-400">Semua penerimaan</p>
                    </div>
                </a>
                <a href="{{ route('feedback.index') }}"
                   class="bg-white rounded-2xl shadow p-4 flex items-center gap-3 hover:shadow-md transition">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#D7F487">
                        <i class="bi bi-star text-sm" style="color:#4E6F5C"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold" style="color:#4E6F5C">Ulasan</p>
                        <p class="text-xs text-gray-400">Beri penilaian</p>
                    </div>
                </a>
            </div>

        </div>
    </main>
</div>
@endsection