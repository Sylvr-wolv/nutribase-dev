{{-- resources/views/menu/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Menu — ' . $menu->nama_menu)

@section('content')

<div class="flex min-h-screen bg-[#FAFCFB]" x-data="{ sidebarOpen: false }">

    @include('layouts.sidebar')

    <main class="flex-1 flex flex-col min-w-0 lg:pl-64">

        {{-- Mobile Header --}}
        <header class="lg:hidden flex items-center justify-between bg-white border-b border-[#DFF0E5] px-4 py-3 sticky top-0 z-20">
            <button type="button" @click="sidebarOpen = true" class="text-[#4E6F5C] hover:text-[#06B13D] transition">
                <i class="bi bi-list text-2xl"></i>
            </button>
            <span class="font-bold text-[#06B13D] text-sm">NutriBase</span>
            <div class="w-8"></div>
        </header>

        <div class="flex-1 p-4 sm:p-6 lg:p-8">

            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2 text-sm text-[#8A9E90] mb-5">
                @php $backRoute = route('menu.index'); @endphp
                <a href="{{ $backRoute }}" class="text-[#4E6F5C] font-medium hover:text-[#06B13D] transition">
                    <i class="bi bi-basket2 mr-1"></i>Menu & Stok
                </a>
                <span class="text-[#C8DDD0]">›</span>
                <span class="truncate">{{ $menu->nama_menu }}</span>
            </div>

            {{-- Back + Edit actions --}}
            <div class="flex items-center gap-2 mb-6">
                <a href="{{ $backRoute }}"
                   class="inline-flex items-center gap-2 px-4 py-2 border border-[#C9DED0] bg-[#F2F8F4] hover:bg-[#D7F487] hover:border-[#79C80E] text-[#4E6F5C] text-sm font-semibold rounded-xl transition">
                    <i class="bi bi-arrow-left text-xs"></i> Kembali
                </a>
                <a href="{{ route('menu.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 border border-blue-200 bg-blue-50 hover:bg-blue-100 text-blue-700 text-sm font-semibold rounded-xl transition">
                    <i class="bi bi-pencil text-xs"></i> Edit
                </a>
                <form method="POST" action="{{ route('menu.destroy', $menu) }}"
                    onsubmit="return confirm('Hapus menu ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 border border-red-200 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-semibold rounded-xl transition">
                        <i class="bi bi-trash text-xs"></i> Hapus
                    </button>
                </form>
                @endif
            </div>

            {{-- Flash --}}
            @if(session('success'))
                <div class="mb-5 flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm">
                    <i class="bi bi-check-circle-fill shrink-0"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- Kolom kiri: Info menu --}}
                <div class="space-y-4">

                    {{-- Card utama --}}
                    <div class="bg-white border border-[#DFF0E5] rounded-2xl overflow-hidden">
                        <div class="h-2 w-full {{ $menu->stok === 0 ? 'bg-red-400' : ($menu->stok <= 10 ? 'bg-amber-400' : 'bg-[#79C80E]') }}"></div>
                        @if($menu->gambar)
                            <div class="w-full h-48 overflow-hidden">
                                <img src="{{ Storage::url($menu->gambar) }}"
                                    alt="{{ $menu->nama_menu }}"
                                    class="w-full h-full object-cover">
                            </div>
                        @endif
                        <div class="p-5 flex flex-col items-center text-center">
                            <div class="w-80 h-80 mb-4">
                                @if($menu->gambar)
                                    <img src="{{ Storage::url($menu->gambar) }}"
                                         alt="{{ $menu->nama_menu }}"
                                         class="w-full h-full object-cover rounded-2xl border border-[#DFF0E5] shadow-sm">
                                @else
                                    <div class="w-full h-full rounded-2xl bg-[#D7F487] flex items-center justify-center">
                                        <i class="bi bi-egg-fried text-[#4E6F5C] text-3xl"></i>
                                    </div>
                                @endif
                            </div>
                            <h1 class="text-lg font-bold text-[#2E3D33] mb-1">{{ $menu->nama_menu }}</h1>
                            <p class="text-xs text-[#8A9E90]">Dibuat oleh {{ $menu->kader->name ?? '—' }}</p>

                            @php
                                $stokBadge = match(true) {
                                    $menu->stok === 0  => ['bg' => 'bg-red-50',    'text' => 'text-red-600',   'border' => 'border-red-200',   'label' => 'Habis'],
                                    $menu->stok <= 10  => ['bg' => 'bg-amber-50',  'text' => 'text-amber-600', 'border' => 'border-amber-200', 'label' => 'Sedikit'],
                                    default            => ['bg' => 'bg-[#F2F8F4]', 'text' => 'text-[#06B13D]', 'border' => 'border-[#C9DED0]', 'label' => 'Tersedia'],
                                };
                            @endphp
                            <div class="mt-4 flex items-center gap-3">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full border {{ $stokBadge['bg'] }} {{ $stokBadge['text'] }} {{ $stokBadge['border'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    {{ $stokBadge['label'] }}
                                </span>
                                <div class="text-2xl font-bold text-[#4E6F5C]">
                                    {{ number_format($menu->stok) }} <span class="text-sm font-normal text-[#8A9E90]">porsi</span>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-[#F0F5F2] px-5 py-4">
                            <div class="text-xs font-semibold text-[#8A9E90] uppercase tracking-wide mb-2">Deskripsi</div>
                            <p class="text-sm text-[#2E3D33] leading-relaxed">
                                {{ $menu->deskripsi ?: 'Tidak ada deskripsi.' }}
                            </p>
                        </div>

                        <div class="border-t border-[#F0F5F2] px-5 py-4 space-y-2.5">
                            <div class="flex justify-between text-sm">
                                <span class="text-[#8A9E90]">Dibuat</span>
                                <span class="font-medium text-[#2E3D33]">{{ $menu->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-[#8A9E90]">Terakhir diperbarui</span>
                                <span class="font-medium text-[#2E3D33]">{{ $menu->updated_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Mini stats --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-white border border-[#DFF0E5] rounded-2xl p-4 text-center">
                            <div class="text-2xl font-bold text-[#4E6F5C]">{{ $menu->jadwals->count() }}</div>
                            <div class="text-xs text-[#8A9E90] mt-1">Jadwal</div>
                        </div>
                        <div class="bg-white border border-[#DFF0E5] rounded-2xl p-4 text-center">
                            <div class="text-2xl font-bold text-[#4E6F5C]">{{ $menu->distribusis->count() }}</div>
                            <div class="text-xs text-[#8A9E90] mt-1">Distribusi</div>
                        </div>
                    </div>

                </div>

                {{-- Kolom kanan: Riwayat --}}
                <div class="lg:col-span-2 space-y-4">

                    {{-- Jadwal --}}
                    <div class="bg-white border border-[#DFF0E5] rounded-2xl overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-4 border-b border-[#F0F5F2]">
                            <div class="text-xs font-bold uppercase tracking-widest text-[#7A9483]">Jadwal Distribusi</div>
                            <span class="text-xs text-[#8A9E90]">{{ $menu->jadwals->count() }} jadwal</span>
                        </div>
                        @if($menu->jadwals->isEmpty())
                            <div class="py-10 text-center text-[#A0B4A7] text-sm">Belum ada jadwal.</div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-[#F4F9F5]">
                                        <tr>
                                            <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-[#7A9483]">Tanggal</th>
                                            <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-[#7A9483]">RT</th>
                                            <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-[#7A9483]">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#F0F5F2]">
                                        @foreach($menu->jadwals->sortByDesc('tanggal')->take(8) as $j)
                                        <tr class="hover:bg-[#F7FCF8]">
                                            <td class="px-5 py-3 font-medium text-[#2E3D33] whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($j->tanggal)->format('d M Y') }}
                                            </td>
                                            <td class="px-5 py-3 text-[#4E6F5C]">RT {{ $j->rt }}</td>
                                            <td class="px-5 py-3 text-xs text-[#6B7A6F]">{{ $j->keterangan ?: '—' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($menu->jadwals->count() > 8)
                                <div class="px-5 py-3 border-t border-[#F0F5F2] text-xs text-[#8A9E90]">
                                    Menampilkan 8 dari {{ $menu->jadwals->count() }} jadwal
                                </div>
                            @endif
                        @endif
                    </div>

                    {{-- Distribusi --}}
                    <div class="bg-white border border-[#DFF0E5] rounded-2xl overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-4 border-b border-[#F0F5F2]">
                            <div class="text-xs font-bold uppercase tracking-widest text-[#7A9483]">Riwayat Distribusi</div>
                            <span class="text-xs text-[#8A9E90]">{{ $menu->distribusis->count() }} transaksi</span>
                        </div>
                        @if($menu->distribusis->isEmpty())
                            <div class="py-10 text-center text-[#A0B4A7] text-sm">Belum ada distribusi.</div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-[#F4F9F5]">
                                        <tr>
                                            <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-[#7A9483]">Waktu</th>
                                            <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-[#7A9483]">Penerima</th>
                                            <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-[#7A9483]">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#F0F5F2]">
                                        @foreach($menu->distribusis->sortByDesc('waktu_distribusi')->take(8) as $d)
                                        <tr class="hover:bg-[#F7FCF8]">
                                            <td class="px-5 py-3 whitespace-nowrap">
                                                <div class="font-medium text-[#2E3D33] text-xs">{{ \Carbon\Carbon::parse($d->waktu_distribusi)->format('d M Y') }}</div>
                                                <div class="text-[11px] text-[#8A9E90]">{{ \Carbon\Carbon::parse($d->waktu_distribusi)->format('H:i') }}</div>
                                            </td>
                                            <td class="px-5 py-3 text-[#4E6F5C]">
                                                {{ $d->penerima->user->name ?? '—' }}
                                            </td>
                                            <td class="px-5 py-3">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold
                                                    {{ $d->status === 'diterima' ? 'bg-emerald-50 text-emerald-700' :
                                                       ($d->status === 'pending'  ? 'bg-amber-50 text-amber-700'   : 'bg-red-50 text-red-700') }}">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                                    {{ ucfirst($d->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($menu->distribusis->count() > 8)
                                <div class="px-5 py-3 border-t border-[#F0F5F2] text-xs text-[#8A9E90]">
                                    Menampilkan 8 dari {{ $menu->distribusis->count() }} distribusi
                                </div>
                            @endif
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </main>
</div>

@endsection