{{-- resources/views/penerima/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Penerima')

@section('content')

@php
    // Buka modal otomatis jika ada validation error yang dikembalikan dari store/update
    $autoOpenCreate = session('open_modal') === 'create' || ($errors->any() && old('_method') === null && old('name'));
    $autoOpenEditId = session('open_modal_edit');
@endphp

<div
    class="flex min-h-screen bg-[#FAFCFB]"
    x-data="{
        sidebarOpen: false,
        modal: @js($autoOpenCreate ? 'create' : ($autoOpenEditId ? 'edit' : null)),
        editId: @js($autoOpenEditId),
        editData: {},
        kategori: @js(old('kategori', '')),

        openCreate() {
            this.modal = 'create';
            this.editId = null;
            this.editData = {};
            this.kategori = '';
            this.$nextTick(() => this.$refs.modalNama?.focus());
        },

        openEdit(data) {
            this.modal = 'edit';
            this.editId = data.id;
            this.editData = data;
            this.kategori = data.kategori;
            this.$nextTick(() => this.$refs.modalNama?.focus());
        },

        closeModal() {
            this.modal = null;
            this.editId = null;
            this.editData = {};
            this.kategori = '';
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
                    <h1 class="text-xl sm:text-2xl font-bold text-[#4E6F5C] tracking-tight">Data Penerima</h1>
                    <p class="text-sm text-[#6B7A6F] mt-1">
                        @if(auth()->user()->role === 'koordinator')
                            Pantau seluruh penerima bantuan MBG
                        @else
                            Kelola data penerima bantuan MBG
                        @endif
                    </p>
                </div>
                @if(auth()->user()->role === 'kader')
                    <button
                        type="button"
                        @click="openCreate()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#06B13D] hover:bg-[#059933] text-white text-sm font-semibold rounded-xl transition self-start shrink-0">
                        <i class="bi bi-plus-lg"></i> Tambah Penerima
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
            {{-- @php $total = max($stats['total'], 1); @endphp
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 mb-6">
                @foreach([
                    ['label' => 'Ibu Hamil', 'val' => $stats['ibu_hamil'],    'pct' => round($stats['ibu_hamil']/$total*100)],
                    ['label' => 'Menyusui',  'val' => $stats['ibu_menyusui'], 'pct' => round($stats['ibu_menyusui']/$total*100)],
                    ['label' => 'Balita',    'val' => $stats['balita'],       'pct' => round($stats['balita']/$total*100)],
                    ['label' => 'Lainnya',   'val' => $stats['lainnya'],      'pct' => round($stats['lainnya']/$total*100)],
                ] as $s)
                    <div class="bg-white border border-[#DFF0E5] rounded-2xl p-4">
                        <div class="text-[10px] font-bold uppercase tracking-widest text-[#8A9E90]">{{ $s['label'] }}</div>
                        <div class="text-3xl font-bold text-[#4E6F5C] mt-1.5">{{ $s['val'] }}</div>
                        <div class="h-1 bg-[#DFF0E5] rounded-full mt-4 overflow-hidden">
                            <div class="h-full bg-[#79C80E] rounded-full" style="width:{{ $s['pct'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div> --}}

            {{-- Toolbar --}}
            @php
                $indexRoute = auth()->user()->role === 'koordinator'
                    ? route('koordinator.laporan.penerima')
                    : route('penerima.index');
            @endphp
            <form method="GET" action="{{ $indexRoute }}" class="mb-5">
                <div class="bg-white border border-[#DFF0E5] rounded-2xl p-3 flex flex-col sm:flex-row flex-wrap gap-2 items-stretch sm:items-center">
                    <div class="relative flex-1 min-w-0">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-[#A0B4A7] text-sm pointer-events-none"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full pl-9 pr-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] rounded-xl text-sm focus:outline-none"
                            placeholder="Nama, NIK, atau RT...">
                    </div>
                    <select name="kategori" onchange="this.form.submit()"
                        class="px-3 py-2.5 bg-[#FAFCFB] border border-[#CCDFD4] focus:border-[#79C80E] rounded-xl text-sm focus:outline-none">
                        <option value="">Semua Kategori</option>
                        <option value="ibu_hamil"    {{ request('kategori') === 'ibu_hamil'    ? 'selected' : '' }}>Ibu Hamil</option>
                        <option value="ibu_menyusui" {{ request('kategori') === 'ibu_menyusui' ? 'selected' : '' }}>Ibu Menyusui</option>
                        <option value="balita"       {{ request('kategori') === 'balita'       ? 'selected' : '' }}>Balita</option>
                        <option value="lainnya"      {{ request('kategori') === 'lainnya'      ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    <div class="flex gap-2">
                        <button type="submit"
                            class="flex-1 sm:flex-none px-5 py-2.5 bg-[#06B13D] hover:bg-[#059933] text-white text-sm font-semibold rounded-xl flex items-center justify-center gap-1.5 transition">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        @if(request('search') || request('kategori'))
                            <a href="{{ $indexRoute }}"
                               class="flex-1 sm:flex-none px-4 py-2.5 border border-[#CCDFD4] text-[#4E6F5C] text-sm font-medium rounded-xl hover:bg-[#F2F8F4] flex items-center justify-center transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            {{-- Table --}}
            <div class="bg-white border border-[#DFF0E5] rounded-2xl overflow-hidden">

                @if($penerima->isEmpty())
                    <div class="py-20 text-center text-[#A0B4A7]">
                        <i class="bi bi-people text-5xl block mb-3"></i>
                        <p class="text-sm">Tidak ada data penerima ditemukan.</p>
                    </div>
                @else

                    {{-- Desktop table --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-[#F2F8F4]">
                                <tr>
                                    <th class="px-5 py-4 text-left text-[10px] font-bold uppercase tracking-widest text-[#7A9483]">Nama</th>
                                    <th class="px-5 py-4 text-left text-[10px] font-bold uppercase tracking-widest text-[#7A9483]">NIK</th>
                                    <th class="px-5 py-4 text-left text-[10px] font-bold uppercase tracking-widest text-[#7A9483]">RT</th>
                                    <th class="px-5 py-4 text-left text-[10px] font-bold uppercase tracking-widest text-[#7A9483]">Kategori</th>
                                    <th class="px-5 py-4 text-left text-[10px] font-bold uppercase tracking-widest text-[#7A9483]">Est. Selesai</th>
                                    <th class="px-5 py-4 text-left text-[10px] font-bold uppercase tracking-widest text-[#7A9483]">Terdaftar</th>
                                    <th class="px-5 py-4 w-36"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#F0F5F2]">
                                @foreach($penerima as $p)
                                    @php
                                        $daysLeft = now()->diffInDays($p->estimasi_durasi, false);
                                        $estColor = $daysLeft > 30 ? 'text-[#06B13D]' : ($daysLeft > 0 ? 'text-amber-600' : 'text-red-600');
                                        $estLabel = $daysLeft > 0
                                            ? $p->estimasi_durasi->format('d M Y') . " ({$daysLeft}h)"
                                            : $p->estimasi_durasi->format('d M Y') . ' ✓';
                                    @endphp
                                    <tr class="hover:bg-[#F7FCF8] transition-colors">
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-[#D7F487] flex items-center justify-center text-xs font-bold text-[#4E6F5C] shrink-0">
                                                    {{ strtoupper(substr($p->user->name ?? '-', 0, 1)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="font-semibold text-[#2E3D33] truncate max-w-[140px]">{{ $p->user->name ?? '-' }}</div>
                                                    <div class="text-xs text-[#8A9E90]">{{ $p->user->username ?? '' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 font-mono text-xs tracking-wider text-[#4E6F5C]">{{ $p->nik }}</td>
                                        <td class="px-5 py-4 text-[#4E6F5C] font-medium">RT {{ $p->rt }}</td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full whitespace-nowrap
                                                @if($p->kategori === 'ibu_hamil') bg-amber-100 text-amber-800
                                                @elseif($p->kategori === 'ibu_menyusui') bg-blue-100 text-blue-800
                                                @elseif($p->kategori === 'balita') bg-[#D7F487] text-[#3A5C0D]
                                                @else bg-[#F0F5F2] text-[#4E6F5C] @endif">
                                                {{ match($p->kategori) {
                                                    'ibu_hamil'    => 'Ibu Hamil',
                                                    'ibu_menyusui' => 'Menyusui',
                                                    'balita'       => 'Balita',
                                                    default        => 'Lainnya',
                                                } }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="text-xs font-medium {{ $estColor }}">{{ $estLabel }}</span>
                                        </td>
                                        <td class="px-5 py-4 text-xs text-[#8A9E90] whitespace-nowrap">
                                            {{ $p->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-1.5">
                                                @if(auth()->user()->role === 'koordinator')
                                                    <a href="{{ route('koordinator.laporan.penerima.show', $p) }}"
                                                       class="px-3 py-1.5 text-xs font-semibold border border-[#C9DED0] hover:bg-[#D7F487] hover:border-[#79C80E] text-[#4E6F5C] rounded-lg transition flex items-center gap-1">
                                                        <i class="bi bi-eye"></i> Detail
                                                    </a>
                                                @else
                                                    <a href="{{ route('penerima.show', $p) }}"
                                                       title="Detail"
                                                       class="p-1.5 text-sm border border-[#C9DED0] hover:bg-[#D7F487] hover:border-[#79C80E] text-[#4E6F5C] rounded-lg transition">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <button
                                                        type="button"
                                                        title="Edit"
                                                        @click="openEdit({
                                                            id: {{ $p->id }},
                                                            name: @js($p->user->name ?? ''),
                                                            username: @js($p->user->username ?? ''),
                                                            nik: @js($p->nik),
                                                            no_telepon: @js($p->no_telepon ?? ''),
                                                            alamat: @js($p->alamat),
                                                            rt: @js($p->rt),
                                                            kategori: @js($p->kategori),
                                                            deskripsi_kategori: @js($p->deskripsi_kategori ?? ''),
                                                            estimasi_durasi: @js($p->estimasi_durasi->format('Y-m-d')),
                                                        })"
                                                        class="p-1.5 text-sm border border-blue-200 hover:bg-blue-50 text-blue-700 rounded-lg transition">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <form method="POST" action="{{ route('penerima.destroy', $p) }}"
                                                          onsubmit="return confirm('Hapus penerima ini?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" title="Hapus"
                                                            class="p-1.5 text-sm border border-red-200 hover:bg-red-50 text-red-600 rounded-lg transition">
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

                    {{-- Mobile card list --}}
                    <div class="md:hidden divide-y divide-[#F0F5F2]">
                        @foreach($penerima as $p)
                            @php
                                $daysLeft = now()->diffInDays($p->estimasi_durasi, false);
                                $estColor = $daysLeft > 30 ? 'text-[#06B13D]' : ($daysLeft > 0 ? 'text-amber-600' : 'text-red-600');
                                $estLabel = $daysLeft > 0
                                    ? $p->estimasi_durasi->format('d M Y') . " ({$daysLeft} hari lagi)"
                                    : $p->estimasi_durasi->format('d M Y') . ' (selesai)';
                            @endphp
                            <div class="p-4 hover:bg-[#F7FCF8] transition-colors">
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-xl bg-[#D7F487] flex items-center justify-center text-sm font-bold text-[#4E6F5C] shrink-0">
                                        {{ strtoupper(substr($p->user->name ?? '-', 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-[#2E3D33] truncate">{{ $p->user->name ?? '-' }}</div>
                                        <div class="text-xs text-[#8A9E90]">{{ $p->user->username ?? '' }}</div>
                                    </div>
                                    <span class="inline-flex px-2.5 py-1 text-[10px] font-semibold rounded-full shrink-0
                                        @if($p->kategori === 'ibu_hamil') bg-amber-100 text-amber-800
                                        @elseif($p->kategori === 'ibu_menyusui') bg-blue-100 text-blue-800
                                        @elseif($p->kategori === 'balita') bg-[#D7F487] text-[#3A5C0D]
                                        @else bg-[#F0F5F2] text-[#4E6F5C] @endif">
                                        {{ match($p->kategori) {
                                            'ibu_hamil'    => 'Ibu Hamil',
                                            'ibu_menyusui' => 'Menyusui',
                                            'balita'       => 'Balita',
                                            default        => 'Lainnya',
                                        } }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs mb-3 pl-[52px]">
                                    <div>
                                        <span class="text-[#8A9E90] block mb-0.5">NIK</span>
                                        <span class="font-mono text-[#4E6F5C] tracking-wider">{{ $p->nik }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[#8A9E90] block mb-0.5">RT</span>
                                        <span class="font-medium text-[#2E3D33]">RT {{ $p->rt }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[#8A9E90] block mb-0.5">Est. Selesai</span>
                                        <span class="font-medium {{ $estColor }}">{{ $estLabel }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[#8A9E90] block mb-0.5">Terdaftar</span>
                                        <span class="text-[#2E3D33]">{{ $p->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 pt-2.5 border-t border-[#F0F5F2]">
                                    @if(auth()->user()->role === 'koordinator')
                                        <a href="{{ route('koordinator.laporan.penerima.show', $p) }}"
                                           class="flex-1 py-2 text-xs font-semibold text-center border border-[#C9DED0] hover:bg-[#D7F487] text-[#4E6F5C] rounded-xl transition flex items-center justify-center gap-1.5">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    @else
                                        <a href="{{ route('penerima.show', $p) }}"
                                           class="flex-1 py-2 text-xs font-semibold text-center border border-[#C9DED0] hover:bg-[#D7F487] text-[#4E6F5C] rounded-xl transition flex items-center justify-center gap-1.5">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                        <button
                                            type="button"
                                            @click="openEdit({
                                                id: {{ $p->id }},
                                                name: @js($p->user->name ?? ''),
                                                username: @js($p->user->username ?? ''),
                                                nik: @js($p->nik),
                                                no_telepon: @js($p->no_telepon ?? ''),
                                                alamat: @js($p->alamat),
                                                rt: @js($p->rt),
                                                kategori: @js($p->kategori),
                                                deskripsi_kategori: @js($p->deskripsi_kategori ?? ''),
                                                estimasi_durasi: @js($p->estimasi_durasi->format('Y-m-d')),
                                            })"
                                            class="flex-1 py-2 text-xs font-semibold border border-blue-200 hover:bg-blue-50 text-blue-700 rounded-xl transition flex items-center justify-center gap-1.5">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <form method="POST" action="{{ route('penerima.destroy', $p) }}"
                                              onsubmit="return confirm('Hapus penerima ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="py-2 px-3 text-xs font-semibold border border-red-200 hover:bg-red-50 text-red-600 rounded-xl transition flex items-center gap-1">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="px-5 py-4 border-t border-[#DFF0E5] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-sm">
                        <div class="text-[#8A9E90] text-xs">
                            Menampilkan
                            <span class="font-semibold text-[#4E6F5C]">{{ $penerima->firstItem() }}</span>–<span class="font-semibold text-[#4E6F5C]">{{ $penerima->lastItem() }}</span>
                            dari {{ $penerima->total() }} penerima
                        </div>
                        {{ $penerima->links() }}
                    </div>

                @endif
            </div>

        </div>
    </main>

    {{-- ══════════════════════════════════════════
         MODAL — Pure Alpine, zero page navigation
         ══════════════════════════════════════════ --}}
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
            {{-- Backdrop --}}
            <div
                class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
                @click="closeModal()"
            ></div>

            {{-- Modal panel --}}
            <div
                x-show="modal !== null"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                class="relative z-10 w-full max-w-lg max-h-[90vh] flex flex-col bg-white rounded-2xl shadow-2xl border border-[#DFF0E5]"
                @click.stop
            >
                {{-- Modal Header --}}
                <div class="flex items-start justify-between gap-3 px-5 py-4 border-b border-[#F0F5F2] shrink-0">
                    <div>
                        <h2 class="text-base font-bold text-[#4E6F5C]" x-text="modal === 'edit' ? 'Edit Penerima' : 'Tambah Penerima'"></h2>
                        <p class="text-xs text-[#8A9E90] mt-0.5" x-text="modal === 'edit' ? 'Perbarui data penerima bantuan MBG' : 'Akun login dibuat otomatis (password awal = NIK)'"></p>
                    </div>
                    <button type="button" @click="closeModal()"
                        class="p-1.5 rounded-lg text-[#8A9E90] hover:bg-[#F0F5F2] hover:text-[#4E6F5C] transition shrink-0">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="overflow-y-auto flex-1 px-5 py-4">

                    {{-- Validation errors (hanya tampil jika ada error dan modal terbuka via PHP) --}}
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
                    <form
                        x-show="modal === 'create'"
                        method="POST"
                        action="{{ route('penerima.store') }}"
                        x-data="{ kategori: '' }"
                    >
                        @csrf
                        @include('penerima.form_fields', ['editMode' => false])
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
                    <form
                        x-show="modal === 'edit'"
                        method="POST"
                        :action="`{{ url('kader/penerima') }}/${editId}`"
                        x-data="{ get kategori() { return $root.kategori; }, set kategori(v) { $root.kategori = v; } }"
                    >
                        @csrf
                        @method('PUT')
                        @include('penerima.form_fields', ['editMode' => true])
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