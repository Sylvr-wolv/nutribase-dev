{{-- resources/views/menu/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Menu & Stok')

@section('content')

@php
    $autoOpenCreate = $errors->any() && !old('_method') && old('nama_menu');
    $autoOpenEditId = $errors->any() && old('_method') === 'PUT' ? request('_edit_id') : null;
@endphp

<div
    class="flex min-h-screen bg-[#FAFCFB]"
    x-data="{
        sidebarOpen: false,
        modal: @js($autoOpenCreate ? 'create' : ($autoOpenEditId ? 'edit' : null)),
        editId: @js($autoOpenEditId),
        editData: {},

        openCreate() {
            this.modal = 'create';
            this.editId = null;
            this.editData = {};
            this.$nextTick(() => this.$refs.modalNama?.focus());
        },

        openEdit(data) {
            this.modal = 'edit';
            this.editId = data.id;
            this.editData = data;
            this.$nextTick(() => this.$refs.editNama?.focus());
        },

        closeModal() {
            this.modal = null;
            this.editId = null;
            this.editData = {};
        }
    }"
    @keydown.escape.window="closeModal()"
>

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

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-6">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-[#4E6F5C] tracking-tight">Menu & Stok</h1>
                    <p class="text-sm text-[#6B7A6F] mt-1">
                        @if(auth()->user()->role === 'koordinator')
                            Pantau daftar menu makanan MBG
                        @elseif(auth()->user()->role === 'penerima')
                            Menu makanan bantuan yang tersedia
                        @else
                            Kelola menu dan stok makanan MBG
                        @endif
                    </p>
                </div>
                @if(auth()->user()->role === 'kader')
                    <button type="button" @click="openCreate()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#06B13D] hover:bg-[#059933] text-white text-sm font-semibold rounded-xl transition self-start shrink-0">
                        <i class="bi bi-plus-lg"></i> Tambah Menu
                    </button>
                @endif
            </div>

            {{-- Flash --}}
            @if(session('success'))
                <div class="mb-5 flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm">
                    <i class="bi bi-check-circle-fill text-base shrink-0"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-5 flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm">
                    <i class="bi bi-x-circle-fill text-base shrink-0"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Stats --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
                <div class="bg-white border border-[#DFF0E5] rounded-2xl p-4">
                    <div class="text-[10px] font-bold uppercase tracking-widest text-[#8A9E90]">Total Menu</div>
                    <div class="text-3xl font-bold text-[#4E6F5C] mt-1.5">{{ $stats['total'] }}</div>
                    <div class="h-1 bg-[#DFF0E5] rounded-full mt-4"><div class="h-full bg-[#79C80E] rounded-full w-full"></div></div>
                </div>
                <div class="bg-white border border-[#DFF0E5] rounded-2xl p-4">
                    <div class="text-[10px] font-bold uppercase tracking-widest text-[#8A9E90]">Total Stok</div>
                    <div class="text-3xl font-bold text-[#4E6F5C] mt-1.5">{{ number_format($stats['stok_total']) }}</div>
                    <div class="h-1 bg-[#DFF0E5] rounded-full mt-4"><div class="h-full bg-[#79C80E] rounded-full w-full"></div></div>
                </div>
                <div class="bg-white border border-[#DFF0E5] rounded-2xl p-4">
                    <div class="text-[10px] font-bold uppercase tracking-widest text-[#8A9E90]">Stok Sedikit</div>
                    <div class="text-3xl font-bold text-amber-500 mt-1.5">{{ $stats['stok_sedikit'] }}</div>
                    <div class="h-1 bg-amber-100 rounded-full mt-4"><div class="h-full bg-amber-400 rounded-full" style="width:{{ $stats['total'] > 0 ? round($stats['stok_sedikit']/$stats['total']*100) : 0 }}%"></div></div>
                </div>
                <div class="bg-white border border-[#DFF0E5] rounded-2xl p-4">
                    <div class="text-[10px] font-bold uppercase tracking-widest text-[#8A9E90]">Stok Habis</div>
                    <div class="text-3xl font-bold text-red-500 mt-1.5">{{ $stats['stok_habis'] }}</div>
                    <div class="h-1 bg-red-100 rounded-full mt-4"><div class="h-full bg-red-400 rounded-full" style="width:{{ $stats['total'] > 0 ? round($stats['stok_habis']/$stats['total']*100) : 0 }}%"></div></div>
                </div>
            </div>

            {{-- Search --}}
            @php
                $menuRoute = match(auth()->user()->role) {
                    'koordinator' => route('laporan.menu'),
                    'penerima'    => route('menu'),
                    default       => route('menu.index'),
                };
            @endphp
            <form method="GET" action="{{ $menuRoute }}" class="mb-5">
                <div class="bg-white border border-[#DFF0E5] rounded-2xl p-3 flex gap-2 items-center">
                    <div class="relative flex-1">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-[#A0B4A7] text-sm pointer-events-none"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full pl-9 pr-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] rounded-xl text-sm focus:outline-none"
                            placeholder="Cari nama atau deskripsi menu...">
                    </div>
                    <button type="submit"
                        class="px-5 py-2.5 bg-[#06B13D] hover:bg-[#059933] text-white text-sm font-semibold rounded-xl flex items-center gap-1.5 transition shrink-0">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ $menuRoute }}"
                           class="px-4 py-2.5 border border-[#CCDFD4] text-[#4E6F5C] text-sm font-medium rounded-xl hover:bg-[#F2F8F4] transition shrink-0">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            {{-- Table / Empty --}}
@if($menu->isEmpty())
<div class="bg-white border border-[#DFF0E5] rounded-2xl py-20 text-center text-[#A0B4A7]">
    <i class="bi bi-basket2 text-5xl block mb-3 text-[#C8DDD0]"></i>
    <p class="text-sm">Belum ada menu yang ditambahkan.</p>
</div>
@else

{{-- Desktop Table --}}
<div class="hidden md:block bg-white border border-[#DFF0E5] rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="border-b border-[#DFF0E5] bg-[#F7FBF8]">
                <tr>
                    <th class="py-3 px-4 text-[10px] font-bold uppercase tracking-widest text-[#8A9E90]">Gambar</th>
                    <th class="py-3 px-4 text-[10px] font-bold uppercase tracking-widest text-[#8A9E90]">Nama Menu</th>
                    <th class="py-3 px-4 text-[10px] font-bold uppercase tracking-widest text-[#8A9E90]">Deskripsi</th>
                    <th class="py-3 px-4 text-[10px] font-bold uppercase tracking-widest text-[#8A9E90]">Kader</th>
                    <th class="py-3 px-4 text-[10px] font-bold uppercase tracking-widest text-[#8A9E90]">Stok</th>
                    <th class="py-3 px-4 text-[10px] font-bold uppercase tracking-widest text-[#8A9E90]">Status</th>
                    <th class="py-3 px-4 text-[10px] font-bold uppercase tracking-widest text-[#8A9E90]">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F0F5F2]">
                @foreach($menu as $m)
                    @php
                        $stokColor = match(true) {
                            $m->stok === 0  => ['bg' => 'bg-red-50',    'text' => 'text-red-600',   'border' => 'border-red-200',   'label' => 'Habis'],
                            $m->stok <= 10  => ['bg' => 'bg-amber-50',  'text' => 'text-amber-600', 'border' => 'border-amber-200', 'label' => 'Sedikit'],
                            default         => ['bg' => 'bg-[#F2F8F4]', 'text' => 'text-[#06B13D]', 'border' => 'border-[#C9DED0]', 'label' => 'Tersedia'],
                        };
                    @endphp
                    <tr class="hover:bg-[#FAFCFB] transition-colors">

                        {{-- Gambar --}}
                        <td class="py-3 px-4">
                            @if($m->gambar)
                                <img src="{{ Storage::url($m->gambar) }}"
                                     alt="{{ $m->nama_menu }}"
                                     class="w-16 h-16 rounded-xl object-cover border border-[#DFF0E5] transition-transform duration-200 hover:scale-105"
                            @else
                                <div class="w-16 h-16 rounded-xl bg-[#D7F487] flex items-center justify-center">
                                    <i class="bi bi-egg-fried text-[#4E6F5C]"></i>
                                </div>
                            @endif
                        </td>

                        {{-- Nama --}}
                        <td class="py-3 px-4">
                            <span class="font-semibold text-[#2E3D33]">{{ $m->nama_menu }}</span>
                        </td>

                        {{-- Deskripsi --}}
                        <td class="py-3 px-4 max-w-[200px]">
                            <span class="text-xs text-[#6B7A6F] line-clamp-2">{{ $m->deskripsi ?: '—' }}</span>
                        </td>

                        {{-- Kader --}}
                        <td class="py-3 px-4 text-xs text-[#6B7A6F] whitespace-nowrap">
                            {{ $m->kader->name ?? '—' }}
                        </td>

                        {{-- Stok --}}
                        <td class="py-3 px-4">
                            <span class="font-bold text-[#4E6F5C]">{{ number_format($m->stok) }}</span>
                            <span class="text-[10px] text-[#8A9E90] ml-0.5">porsi</span>
                        </td>

                        {{-- Status --}}
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full border
                                {{ $stokColor['bg'] }} {{ $stokColor['text'] }} {{ $stokColor['border'] }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                {{ $stokColor['label'] }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('menu.show', $m) }}"
                                   class="p-1.5 text-xs border border-[#C9DED0] hover:bg-[#D7F487] hover:border-[#79C80E] text-[#4E6F5C] rounded-lg transition flex items-center gap-1">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(auth()->user()->role === 'kader')
                                    <button type="button"
                                        @click="openEdit({
                                            id: {{ $m->id }},
                                            nama_menu: @js($m->nama_menu),
                                            deskripsi: @js($m->deskripsi ?? ''),
                                            stok: {{ $m->stok }},
                                            gambar: @js($m->gambar ?? ''),
                                        })"
                                        class="p-1.5 text-xs border border-blue-200 hover:bg-blue-50 text-blue-600 rounded-lg transition">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form method="POST" action="{{ route('menu.destroy', $m) }}"
                                          onsubmit="return confirm('Hapus menu {{ addslashes($m->nama_menu) }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 text-xs border border-red-200 hover:bg-red-50 text-red-500 rounded-lg transition">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Mobile Cards (unchanged) --}}
<div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-4">
    @foreach($menu as $m)
        @php
            $stokColor = match(true) {
                $m->stok === 0  => ['bg' => 'bg-red-50',    'text' => 'text-red-600',   'border' => 'border-red-200',   'label' => 'Habis'],
                $m->stok <= 10  => ['bg' => 'bg-amber-50',  'text' => 'text-amber-600', 'border' => 'border-amber-200', 'label' => 'Sedikit'],
                default         => ['bg' => 'bg-[#F2F8F4]', 'text' => 'text-[#06B13D]', 'border' => 'border-[#C9DED0]', 'label' => 'Tersedia'],
            };
        @endphp
        <div class="bg-white border border-[#DFF0E5] rounded-2xl overflow-hidden flex flex-col hover:border-[#A8D5B5] transition-colors">
            <div class="h-1.5 w-full {{ $m->stok === 0 ? 'bg-red-400' : ($m->stok <= 10 ? 'bg-amber-400' : 'bg-[#79C80E]') }}"></div>
            <div class="p-5 flex-1 flex flex-col">
                <div class="flex items-start gap-3 mb-3">
                    @if($m->gambar)
                        <img src="{{ Storage::url($m->gambar) }}" alt="{{ $m->nama_menu }}"
                             class="w-10 h-10 rounded-xl object-cover shrink-0 border border-[#DFF0E5]">
                    @else
                        <div class="w-10 h-10 rounded-xl bg-[#D7F487] flex items-center justify-center shrink-0">
                            <i class="bi bi-egg-fried text-[#4E6F5C] text-lg"></i>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-[#2E3D33] text-sm leading-tight truncate">{{ $m->nama_menu }}</h3>
                        <p class="text-xs text-[#8A9E90] mt-0.5">oleh {{ $m->kader->name ?? '—' }}</p>
                    </div>
                </div>
                <p class="text-xs text-[#6B7A6F] leading-relaxed flex-1 mb-4 line-clamp-2">{{ $m->deskripsi ?: '—' }}</p>
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full border {{ $stokColor['bg'] }} {{ $stokColor['text'] }} {{ $stokColor['border'] }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                        {{ $stokColor['label'] }}
                    </span>
                    <div class="text-right">
                        <div class="text-xl font-bold text-[#4E6F5C]">{{ number_format($m->stok) }}</div>
                        <div class="text-[10px] text-[#8A9E90] uppercase tracking-wide">porsi</div>
                    </div>
                </div>
            </div>
            <div class="px-5 pb-4 flex items-center gap-2 border-t border-[#F0F5F2] pt-3">
                <a href="{{ route('menu.show', $m) }}"
                   class="flex-1 py-2 text-xs font-semibold text-center border border-[#C9DED0] hover:bg-[#D7F487] hover:border-[#79C80E] text-[#4E6F5C] rounded-xl transition flex items-center justify-center gap-1.5">
                    <i class="bi bi-eye"></i> Detail
                </a>
                @if(auth()->user()->role === 'kader')
                    <button type="button"
                        @click="openEdit({ id: {{ $m->id }}, nama_menu: @js($m->nama_menu), deskripsi: @js($m->deskripsi ?? ''), stok: {{ $m->stok }}, gambar: @js($m->gambar ?? '') })"
                        class="p-2 text-sm border border-blue-200 hover:bg-blue-50 text-blue-700 rounded-xl transition">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <form method="POST" action="{{ route('menu.destroy', $m) }}"
                          onsubmit="return confirm('Hapus menu {{ addslashes($m->nama_menu) }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 text-sm border border-red-200 hover:bg-red-50 text-red-600 rounded-xl transition">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @endforeach
</div>

{{-- Pagination --}}
<div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white border border-[#DFF0E5] rounded-2xl px-5 py-4">
    <p class="text-xs text-[#8A9E90] order-2 sm:order-1">
        Menampilkan
        <span class="font-semibold text-[#4E6F5C]">{{ $menu->firstItem() }}</span>–<span class="font-semibold text-[#4E6F5C]">{{ $menu->lastItem() }}</span>
        dari <span class="font-semibold text-[#4E6F5C]">{{ $menu->total() }}</span> menu
    </p>

    <div class="flex items-center gap-1 order-1 sm:order-2">
        {{-- Prev --}}
        <span class="w-9 h-9 flex items-center justify-center rounded-xl {{ $menu->onFirstPage() ? 'text-[#C8DDD0] cursor-not-allowed' : 'text-[#4E6F5C] hover:bg-[#F2F8F4] cursor-pointer' }}">
            @if(!$menu->onFirstPage())
                <a href="{{ $menu->previousPageUrl() }}" class="w-full h-full flex items-center justify-center rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </a>
            @else
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            @endif
        </span>

        {{-- Page numbers --}}
        @foreach($menu->getUrlRange(max(1, $menu->currentPage() - 2), min($menu->lastPage(), $menu->currentPage() + 2)) as $page => $url)
            @if($page == $menu->currentPage())
                <span class="w-9 h-9 flex items-center justify-center rounded-xl text-white text-sm font-bold bg-[#06B13D]">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center rounded-xl text-[#4E6F5C] hover:bg-[#F2F8F4] text-sm transition">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next --}}
        <span class="w-9 h-9 flex items-center justify-center rounded-xl {{ $menu->hasMorePages() ? 'text-[#4E6F5C] hover:bg-[#F2F8F4] cursor-pointer' : 'text-[#C8DDD0] cursor-not-allowed' }}">
            @if($menu->hasMorePages())
                <a href="{{ $menu->nextPageUrl() }}" class="w-full h-full flex items-center justify-center rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            @endif
        </span>
    </div>
</div>

@endif

        </div>
    </main>

    {{-- Modal --}}
    @if(auth()->user()->role === 'kader')
    <template x-teleport="body">
        <div
            x-show="modal !== null"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="display:none;"
        >
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="closeModal()"></div>

            <div
                x-show="modal !== null"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                class="relative z-10 w-full max-w-md max-h-[90vh] flex flex-col bg-white rounded-2xl shadow-2xl border border-[#DFF0E5]"
                @click.stop
            >
                {{-- Modal Header --}}
                <div class="flex items-start justify-between gap-3 px-5 py-4 border-b border-[#F0F5F2] shrink-0">
                    <div>
                        <h2 class="text-base font-bold text-[#4E6F5C]"
                            x-text="modal === 'edit' ? 'Edit Menu' : 'Tambah Menu'"></h2>
                        <p class="text-xs text-[#8A9E90] mt-0.5"
                           x-text="modal === 'edit' ? 'Perbarui data menu makanan' : 'Tambahkan menu makanan baru'"></p>
                    </div>
                    <button type="button" @click="closeModal()"
                        class="p-1.5 rounded-lg text-[#8A9E90] hover:bg-[#F0F5F2] hover:text-[#4E6F5C] transition shrink-0">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="overflow-y-auto flex-1 px-5 py-4">

                    @if($errors->any())
                    <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs">
                        <p class="font-semibold mb-1">Periksa kembali isian:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- CREATE FORM --}}
                    <form x-show="modal === 'create'"
                        method="POST"
                        enctype="multipart/form-data"
                        action="{{ route('menu.store') }}">
                        @csrf
                        @include('menu.form_fields', ['editMode' => false])
                        <div class="flex gap-2 mt-5 pt-4 border-t border-[#F0F5F2]">
                            <button type="submit"
                                class="px-5 py-2.5 bg-[#06B13D] hover:bg-[#059933] text-white text-sm font-semibold rounded-xl flex items-center gap-2 transition">
                                <i class="bi bi-check-lg"></i> Tambah
                            </button>
                            <button type="button" @click="closeModal()"
                                class="px-4 py-2.5 border border-[#CCDFD4] text-[#4E6F5C] text-sm font-medium rounded-xl hover:bg-[#F2F8F4] transition">
                                Batal
                            </button>
                        </div>
                    </form>

                    {{-- EDIT FORM --}}
                    <form x-show="modal === 'edit'"
                        method="POST"
                        enctype="multipart/form-data"
                        x-bind:action="'{{ route('menu.update', ':id') }}'.replace(':id', editId)">
                        @csrf
                        @method('PUT')
                        @include('menu.form_fields', ['editMode' => true])
                        <div class="flex gap-2 mt-5 pt-4 border-t border-[#F0F5F2]">
                            <button type="submit"
                                class="px-5 py-2.5 bg-[#06B13D] hover:bg-[#059933] text-white text-sm font-semibold rounded-xl flex items-center gap-2 transition">
                                <i class="bi bi-check-lg"></i> Simpan
                            </button>
                            <button type="button" @click="closeModal()"
                                class="px-4 py-2.5 border border-[#CCDFD4] text-[#4E6F5C] text-sm font-medium rounded-xl hover:bg-[#F2F8F4] transition">
                                Batal
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </template>
    @endif

</div>

@endsection