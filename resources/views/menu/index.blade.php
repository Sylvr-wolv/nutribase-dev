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

            {{-- Grid / Empty --}}
            @if($menu->isEmpty())
                <div class="bg-white border border-[#DFF0E5] rounded-2xl py-20 text-center text-[#A0B4A7]">
                    <i class="bi bi-basket2 text-5xl block mb-3 text-[#C8DDD0]"></i>
                    <p class="text-sm">Belum ada menu yang ditambahkan.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($menu as $m)
                        @php
                            $stokColor = match(true) {
                                $m->stok === 0  => ['bg' => 'bg-red-50',    'text' => 'text-red-600',   'border' => 'border-red-200',   'label' => 'Habis'],
                                $m->stok <= 10  => ['bg' => 'bg-amber-50',  'text' => 'text-amber-600', 'border' => 'border-amber-200', 'label' => 'Sedikit'],
                                default         => ['bg' => 'bg-[#F2F8F4]', 'text' => 'text-[#06B13D]', 'border' => 'border-[#C9DED0]', 'label' => 'Tersedia'],
                            };
                        @endphp
                        <div class="bg-white border border-[#DFF0E5] rounded-2xl overflow-hidden flex flex-col hover:border-[#A8D5B5] transition-colors group">

                            {{-- Card top accent --}}
                            <div class="h-1.5 w-full {{ $m->stok === 0 ? 'bg-red-400' : ($m->stok <= 10 ? 'bg-amber-400' : 'bg-[#79C80E]') }}"></div>

                            <div class="p-5 flex-1 flex flex-col">
                                {{-- Icon + nama --}}
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-xl bg-[#D7F487] flex items-center justify-center shrink-0">
                                        <i class="bi bi-egg-fried text-[#4E6F5C] text-lg"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-[#2E3D33] text-sm leading-tight truncate">{{ $m->nama_menu }}</h3>
                                        <p class="text-xs text-[#8A9E90] mt-0.5">
                                            oleh {{ $m->kader->name ?? '—' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Deskripsi --}}
                                <p class="text-xs text-[#6B7A6F] leading-relaxed flex-1 mb-4 line-clamp-2">
                                    {{ $m->deskripsi ?: '—' }}
                                </p>

                                {{-- Stok badge --}}
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full border {{ $stokColor['bg'] }} {{ $stokColor['text'] }} {{ $stokColor['border'] }}">
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                            {{ $stokColor['label'] }}
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xl font-bold text-[#4E6F5C]">{{ number_format($m->stok) }}</div>
                                        <div class="text-[10px] text-[#8A9E90] uppercase tracking-wide">porsi</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="px-5 pb-4 flex items-center gap-2 border-t border-[#F0F5F2] pt-3">
                                <a href="{{ auth()->user()->role === 'koordinator' ? route('menu.index') : route('menu.show', $m) }}"
                                   @if(auth()->user()->role === 'koordinator')
                                   href="{{ url('koordinator/laporan/menu/'.$m->id) }}"
                                   @elseif(auth()->user()->role === 'penerima')
                                   href="{{ url('penerima/menu/'.$m->id) }}"
                                   @else
                                   href="{{ route('menu.show', $m) }}"
                                   @endif
                                   class="flex-1 py-2 text-xs font-semibold text-center border border-[#C9DED0] hover:bg-[#D7F487] hover:border-[#79C80E] text-[#4E6F5C] rounded-xl transition flex items-center justify-center gap-1.5">
                                    <i class="bi bi-eye"></i> Detail
                                </a>

                                @if(auth()->user()->role === 'kader')
                                    <button
                                        type="button"
                                        @click="openEdit({
                                            id: {{ $m->id }},
                                            nama_menu: @js($m->nama_menu),
                                            deskripsi: @js($m->deskripsi ?? ''),
                                            stok: {{ $m->stok }},
                                        })"
                                        class="p-2 text-sm border border-blue-200 hover:bg-blue-50 text-blue-700 rounded-xl transition">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form method="POST" action="{{ route('menu.destroy', $m) }}"
                                          onsubmit="return confirm('Hapus menu {{ addslashes($m->nama_menu) }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-sm border border-red-200 hover:bg-red-50 text-red-600 rounded-xl transition">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-sm">
                    <div class="text-[#8A9E90] text-xs">
                        Menampilkan
                        <span class="font-semibold text-[#4E6F5C]">{{ $menu->firstItem() }}</span>–<span class="font-semibold text-[#4E6F5C]">{{ $menu->lastItem() }}</span>
                        dari {{ $menu->total() }} menu
                    </div>
                    {{ $menu->links() }}
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
                          :action="`{{ url('kader/menu') }}/${editId}`">
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