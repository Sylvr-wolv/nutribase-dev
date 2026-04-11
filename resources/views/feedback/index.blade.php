@extends('layouts.app')

@section('content')
<div class="flex min-h-screen" style="background:#FAFCFB">

    @include('layouts.sidebar')

    <main
        class="flex-1 flex flex-col min-w-0 lg:pl-72"
        x-data="{
            openModal: false,
            editModal: false,
            editData: {},
            tanggapanModal: false,
            tanggapanFeedback: {},

            filterRating: '{{ request('rating') }}',
            filterDateFrom: '{{ request('date_from') }}',
            filterDateTo: '{{ request('date_to') }}',
            searchQuery: '{{ request('search') }}',

            submitSearch() {
                const params = new URLSearchParams();
                if (this.searchQuery.trim()) params.set('search', this.searchQuery.trim());
                if (this.filterRating)       params.set('rating', this.filterRating);
                if (this.filterDateFrom)     params.set('date_from', this.filterDateFrom);
                if (this.filterDateTo)       params.set('date_to', this.filterDateTo);
                window.location.href = '{{ route('feedback.index') }}' + (params.toString() ? '?' + params.toString() : '');
            },

            ratingColor(r) {
                if (r >= 4) return 'background:#D7F487;color:#4E6F5C';
                if (r === 3) return 'background:#FEF9C3;color:#854D0E';
                return 'background:#FEE2E2;color:#991B1B';
            },
        }">

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

            {{-- Flash --}}
            @if(session('success'))
            <div class="mb-4 flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-white" style="background:#06B13D">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="mb-4 flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-white bg-red-500">
                <i class="bi bi-x-circle-fill"></i>
                {{ session('error') }}
            </div>
            @endif

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 mb-6">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold" style="color:#4E6F5C">Ulasan Penerima</h1>
                    <p class="text-gray-500 text-sm">Feedback dan penilaian dari penerima distribusi</p>
                </div>

                @can('create', App\Models\Feedback::class)
                <button
                    @click="openModal = true"
                    class="self-start sm:self-auto font-medium px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl flex items-center gap-2 shadow-sm transition text-sm sm:text-base whitespace-nowrap text-white"
                    style="background:#06B13D">
                    <span class="bg-white/20 w-6 h-6 flex items-center justify-center rounded-full text-sm flex-shrink-0">+</span>
                    Tambah Ulasan
                </button>
                @endcan
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Total</span>
                    <span class="text-2xl font-black" style="color:#4E6F5C">{{ $stats['total'] }}</span>
                </div>
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Rata-rata</span>
                    <div class="flex items-center gap-1.5">
                        <span class="text-2xl font-black" style="color:#06B13D">{{ number_format($stats['avg_rating'], 1) }}</span>
                        <i class="bi bi-star-fill text-yellow-400 text-sm"></i>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Sudah Ditanggapi</span>
                    <span class="text-2xl font-black" style="color:#06B13D">{{ $stats['ditanggapi'] }}</span>
                </div>
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Belum Ditanggapi</span>
                    <span class="text-2xl font-black text-red-500">{{ $stats['belum_ditanggapi'] }}</span>
                </div>
            </div>

            {{-- Search + Filter --}}
            {{-- Search + Filter --}}
            <div class="bg-white rounded-2xl shadow px-4 sm:px-6 py-4 mb-4" x-data="{ filterOpen: false }">

                {{-- Search row --}}
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                            </svg>
                        </div>
                        <input type="text" x-model="searchQuery" @keydown.enter="submitSearch()"
                            placeholder="Cari nama penerima atau isi ulasan..."
                            class="w-full bg-gray-100 rounded-xl pl-9 pr-9 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300 transition">
                        <button x-show="searchQuery" @click="searchQuery = ''; submitSearch()"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Filter toggle --}}
                    <button @click="filterOpen = !filterOpen"
                        class="relative w-10 h-10 flex items-center justify-center rounded-xl border-2 transition flex-shrink-0"
                        :class="filterOpen
                            ? 'border-green-400 bg-green-50 text-green-600'
                            : 'border-gray-200 bg-gray-100 text-gray-500 hover:border-gray-300'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                        </svg>
                        @if(request('rating') || request('date_from') || request('date_to'))
                        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 rounded-full border-2 border-white" style="background:#06B13D"></span>
                        @endif
                    </button>

                    <button @click="submitSearch()"
                        class="text-white px-5 py-2.5 rounded-xl text-sm font-medium transition whitespace-nowrap"
                        style="background:#06B13D">Cari</button>
                </div>

                {{-- Active filter chips --}}
                @if(request('rating') || request('date_from') || request('date_to'))
                <div class="flex items-center gap-2 flex-wrap mt-2.5">
                    @if(request('rating'))
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full"
                          style="background:#D7F487;color:#4E6F5C">
                        <i class="bi bi-star-fill text-[10px]"></i>
                        {{ request('rating') }} Bintang
                    </span>
                    @endif
                    @if(request('date_from') || request('date_to'))
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full"
                          style="background:#D7F487;color:#4E6F5C">
                        <i class="bi bi-calendar3 text-[10px]"></i>
                        {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('d M') : '...' }}
                        –
                        {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('d M Y') : '...' }}
                    </span>
                    @endif
                </div>
                @endif

                {{-- Collapsible filter panel --}}
                <div x-show="filterOpen"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     class="mt-3 pt-3 border-t border-gray-100 flex flex-col sm:flex-row gap-3 flex-wrap items-end"
                     style="display:none">

                    {{-- Filter Rating --}}
                    <div class="flex flex-col gap-1 min-w-[160px]">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Rating</label>
                        <div class="relative">
                            <select x-model="filterRating" @change="submitSearch()"
                                class="w-full appearance-none bg-gray-100 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300 transition cursor-pointer pr-8">
                                <option value="">Semua Rating</option>
                                @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                                @endfor
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-2.5 flex items-center">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Filter Tanggal --}}
                    <div class="flex flex-col gap-1 flex-1 min-w-[220px]">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Rentang Waktu</label>
                        <div class="flex items-center gap-2">
                            <input type="text" x-model="filterDateFrom"
                                x-init="flatpickr($el, {
                                    locale: 'id',
                                    dateFormat: 'Y-m-d',
                                    altInput: true,
                                    altFormat: 'd M Y',
                                    defaultDate: filterDateFrom || null,
                                    onChange: (d, str) => {
                                        filterDateFrom = str;
                                        if (filterDateFrom && filterDateTo) submitSearch();
                                    }
                                })"
                                placeholder="Tanggal dari"
                                class="flex-1 bg-gray-100 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300 transition">
                            <span class="text-gray-400 text-xs font-medium flex-shrink-0">s/d</span>
                            <input type="text" x-model="filterDateTo"
                                x-init="flatpickr($el, {
                                    locale: 'id',
                                    dateFormat: 'Y-m-d',
                                    altInput: true,
                                    altFormat: 'd M Y',
                                    defaultDate: filterDateTo || null,
                                    onChange: (d, str) => {
                                        filterDateTo = str;
                                        if (filterDateFrom && filterDateTo) submitSearch();
                                    }
                                })"
                                placeholder="Tanggal sampai"
                                class="flex-1 bg-gray-100 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300 transition">
                        </div>
                    </div>

                    {{-- Reset --}}
                    @if(request('search') || request('rating') || request('date_from') || request('date_to'))
                    <a href="{{ route('feedback.index') }}"
                       class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-red-500 bg-gray-100 hover:bg-red-50 px-3 py-2.5 rounded-xl transition whitespace-nowrap">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Reset Filter
                    </a>
                    @endif
                </div>
            </div>

            {{-- Desktop Table --}}
            <div class="hidden md:block bg-white rounded-3xl shadow p-4 sm:p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-gray-500 uppercase text-xs border-b">
                            <tr>
                                <th class="py-4 pr-4">No</th>
                                <th class="pr-4">Penerima</th>
                                <th class="pr-4">Distribusi</th>
                                <th class="pr-4">Rating</th>
                                <th class="pr-4">Ulasan</th>
                                <th class="pr-4">Tanggapan</th>
                                <th class="pr-4">Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($feedbacks as $i => $f)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-4 pr-4 text-gray-500">{{ $feedbacks->firstItem() + $i }}</td>
                                <td class="pr-4">
                                    <span class="font-medium text-gray-700">{{ $f->penerima->nama ?? '-' }}</span>
                                </td>
                                <td class="pr-4 text-xs text-gray-500">
                                    {{ $f->distribusi->waktu_distribusi->format('d M Y') ?? '-' }}<br>
                                    <span class="text-gray-400">{{ $f->distribusi->menu->nama ?? '-' }}</span>
                                </td>
                                <td class="pr-4">
                                    <div class="flex items-center gap-1">
                                        @for($s = 1; $s <= 5; $s++)
                                        <i class="bi bi-star-fill text-xs {{ $s <= $f->rating ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                                        @endfor
                                        <span class="ml-1 text-xs font-bold text-gray-600">{{ $f->rating }}</span>
                                    </div>
                                </td>
                                <td class="pr-4 text-gray-500 text-xs max-w-[200px]">
                                    <span class="block truncate">{{ $f->isi_ulasan ?? '-' }}</span>
                                </td>
                                <td class="pr-4">
                                    @if($f->tanggapans->count() > 0)
                                    <a href="{{ route('feedback.show', $f->id) }}"
                                       class="text-xs px-2.5 py-1 rounded-full font-semibold transition hover:opacity-75"
                                       style="background:#D7F487;color:#4E6F5C">
                                        {{ $f->tanggapans->count() }} tanggapan
                                    </a>
                                    @else
                                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-gray-100 text-gray-400">
                                        Belum ada
                                    </span>
                                    @endif
                                </td>
                                <td class="pr-4 text-xs text-gray-400 whitespace-nowrap">
                                    {{ $f->created_at->format('d M Y') }}
                                </td>
                                <td class="space-x-3 text-xs font-semibold tracking-wide whitespace-nowrap">
                                    @can('create', App\Models\Tanggapan::class)
                                    <button
                                        @click="
                                            tanggapanFeedback = {
                                                id: {{ $f->id }},
                                                penerima: '{{ $f->penerima->nama ?? '-' }}',
                                                rating: {{ $f->rating }},
                                                ulasan: @js($f->isi_ulasan),
                                                tanggapans: @js($f->tanggapans->map(fn($t) => ['id' => $t->id, 'user' => $t->user->name ?? '-', 'isi' => $t->isi_tanggapan, 'tgl' => $t->created_at->format('d M Y H:i')])),
                                            };
                                            tanggapanModal = true
                                        "
                                        class="transition hover:opacity-70"
                                        style="color:#06B13D">TANGGAPI</button>
                                    @endcan

                                    @can('update', $f)
                                    <button
                                        @click="
                                            editData = {
                                                id: {{ $f->id }},
                                                distribusi_id: {{ $f->distribusi_id }},
                                                penerima_id: {{ $f->penerima_id }},
                                                rating: {{ $f->rating }},
                                                isi_ulasan: @js($f->isi_ulasan),
                                                gambar: @js($f->gambar),
                                            };
                                            editModal = true
                                        "
                                        class="transition hover:opacity-70 text-blue-500">EDIT</button>
                                    @endcan

                                    @can('delete', $f)
                                    <form id="del-fb-{{ $f->id }}" action="{{ route('feedback.destroy', $f->id) }}" method="POST" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button type="button"
                                        @click="Swal.fire({ title: 'Hapus ulasan ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#06B13D', cancelButtonColor: '#d33', confirmButtonText: 'Ya, hapus!' }).then(r => { if (r.isConfirmed) document.getElementById('del-fb-{{ $f->id }}').submit() })"
                                        class="text-red-500 hover:text-red-700 transition">HAPUS</button>
                                    @endcan
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-12 text-gray-400">
                                    <i class="bi bi-chat-square-text text-4xl block mb-2"></i>
                                    Belum ada ulasan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Mobile Cards --}}
            <div class="md:hidden space-y-3">
                @forelse($feedbacks as $f)
                <div class="bg-white rounded-2xl shadow p-4">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800">{{ $f->penerima->nama ?? '-' }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $f->created_at->format('d M Y') }}</p>
                        </div>
                        <div class="flex items-center gap-0.5 flex-shrink-0">
                            @for($s = 1; $s <= 5; $s++)
                            <i class="bi bi-star-fill text-xs {{ $s <= $f->rating ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                            @endfor
                        </div>
                    </div>

                    @if($f->isi_ulasan)
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $f->isi_ulasan }}</p>
                    @endif

                    <div class="flex flex-wrap gap-2 mb-3">
                        <span class="text-xs px-2 py-1 rounded-lg bg-gray-100 text-gray-500">
                            {{ $f->distribusi->menu->nama ?? '-' }}
                        </span>
                        @if($f->tanggapans->count() > 0)
                        <a href="{{ route('feedback.show', $f->id) }}"
                           class="text-xs px-2.5 py-1 rounded-full font-semibold transition hover:opacity-75"
                           style="background:#D7F487;color:#4E6F5C">
                            {{ $f->tanggapans->count() }} tanggapan
                        </a>
                        @else
                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-gray-100 text-gray-400">Belum ditanggapi</span>
                        @endif
                    </div>

                    <div class="flex gap-2 pt-3 border-t border-gray-100">
                        @can('create', App\Models\Tanggapan::class)
                        <button
                            @click="
                                tanggapanFeedback = {
                                    id: {{ $f->id }},
                                    penerima: '{{ $f->penerima->nama ?? '-' }}',
                                    rating: {{ $f->rating }},
                                    ulasan: @js($f->isi_ulasan),
                                    gambar: @js($f->gambar),  {{-- ADD THIS --}}
                                    tanggapans: @js($f->tanggapans->map(fn($t) => ['id' => $t->id, 'user' => $t->user->name ?? '-', 'isi' => $t->isi_tanggapan, 'tgl' => $t->created_at->format('d M Y H:i')])),
                                };
                                tanggapanModal = true
                            "
                            class="flex-1 text-xs font-semibold py-2 rounded-lg transition text-white"
                            style="background:#06B13D">
                            TANGGAPI
                        </button>
                        @endcan

                        @can('delete', $f)
                        <button type="button"
                            @click="Swal.fire({ title: 'Hapus ulasan ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#06B13D', cancelButtonColor: '#d33', confirmButtonText: 'Ya, hapus!' }).then(r => { if (r.isConfirmed) document.getElementById('del-fb-{{ $f->id }}').submit() })"
                            class="flex-1 text-xs font-semibold text-red-500 bg-red-50 hover:bg-red-100 py-2 rounded-lg transition">
                            HAPUS
                        </button>
                        @endcan
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-2xl shadow p-10 text-center text-gray-400">
                    <i class="bi bi-chat-square-text text-4xl block mb-2"></i>
                    Belum ada ulasan
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white rounded-2xl shadow px-5 py-4">
                <p class="text-sm text-gray-500 order-2 sm:order-1">
                    Menampilkan <span class="font-semibold text-gray-700">{{ $feedbacks->count() }}</span>
                    dari <span class="font-semibold text-gray-700">{{ $feedbacks->total() }}</span> data
                </p>
                <div class="flex items-center gap-1 order-1 sm:order-2">
                    <span class="w-9 h-9 flex items-center justify-center rounded-xl {{ $feedbacks->onFirstPage() ? 'text-gray-300 cursor-not-allowed' : 'text-gray-500 cursor-pointer' }}">
                        @if(!$feedbacks->onFirstPage())
                            <a href="{{ $feedbacks->previousPageUrl() }}" class="w-full h-full flex items-center justify-center hover:bg-gray-100 rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            </a>
                        @else
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        @endif
                    </span>
                    @foreach($feedbacks->getUrlRange(max(1, $feedbacks->currentPage() - 2), min($feedbacks->lastPage(), $feedbacks->currentPage() + 2)) as $page => $url)
                        @if($page == $feedbacks->currentPage())
                            <span class="w-9 h-9 flex items-center justify-center rounded-xl text-white text-sm font-bold" style="background:#06B13D">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center rounded-xl text-gray-600 hover:bg-gray-100 text-sm transition">{{ $page }}</a>
                        @endif
                    @endforeach
                    <span class="w-9 h-9 flex items-center justify-center rounded-xl {{ $feedbacks->hasMorePages() ? 'text-gray-500 cursor-pointer' : 'text-gray-300 cursor-not-allowed' }}">
                        @if($feedbacks->hasMorePages())
                            <a href="{{ $feedbacks->nextPageUrl() }}" class="w-full h-full flex items-center justify-center hover:bg-gray-100 rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @else
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        @endif
                    </span>
                </div>
            </div>

        </div>

        @include('feedback.form')

    </main>
</div>
@endsection