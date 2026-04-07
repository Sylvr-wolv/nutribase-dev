@extends('layouts.app')

@section('content')
<div class="flex min-h-screen" style="background:#FAFCFB">

    @include('layouts.sidebar')

    <main
        class="flex-1 flex flex-col min-w-0 lg:pl-72"
        x-data="{
            searchQuery: '{{ request('search') }}',
            filterStatus: '{{ request('status') }}',
            filterDateFrom: '{{ request('date_from') }}',
            filterDateTo: '{{ request('date_to') }}',

            submitSearch() {
                const params = new URLSearchParams();
                if (this.searchQuery.trim()) params.set('search', this.searchQuery.trim());
                if (this.filterStatus)       params.set('status', this.filterStatus);
                if (this.filterDateFrom)     params.set('date_from', this.filterDateFrom);
                if (this.filterDateTo)       params.set('date_to', this.filterDateTo);
                window.location.href = '{{ route('riwayat') }}' + (params.toString() ? '?' + params.toString() : '');
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

            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-xl sm:text-2xl font-bold" style="color:#4E6F5C">Riwayat Distribusi</h1>
                <p class="text-gray-500 text-sm">Histori penerimaan bantuan makanan Anda</p>
            </div>

            {{-- Stats --}}
            @if(auth()->user()->role !== 'penerima')
            <div class="grid grid-cols-3 gap-3 mb-5">
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Total</span>
                    <span class="text-2xl font-black" style="color:#4E6F5C">{{ $stats['total'] }}</span>
                </div>
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Diterima</span>
                    <span class="text-2xl font-black" style="color:#06B13D">{{ $stats['diterima'] }}</span>
                </div>
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Gagal/Pending</span>
                    <span class="text-2xl font-black text-red-500">{{ $stats['gagal'] + $stats['pending'] }}</span>
                </div>
            </div>
            @endif

            {{-- Search + Filter --}}
            <div class="bg-white rounded-2xl shadow px-4 sm:px-6 py-4 mb-4" x-data="{ filterOpen: false }">
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                            </svg>
                        </div>
                        <input type="text" x-model="searchQuery" @keydown.enter="submitSearch()"
                            placeholder="Cari nama menu..."
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
                        @if(request('status') || request('date_from') || request('date_to'))
                        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 rounded-full border-2 border-white" style="background:#06B13D"></span>
                        @endif
                    </button>

                    <button @click="submitSearch()"
                        class="text-white px-5 py-2.5 rounded-xl text-sm font-medium transition whitespace-nowrap"
                        style="background:#06B13D">Cari</button>
                </div>

                {{-- Active chips --}}
                @if(request('status') || request('date_from') || request('date_to'))
                <div class="flex items-center gap-2 flex-wrap mt-2.5">
                    @if(request('status'))
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full"
                          style="background:#D7F487;color:#4E6F5C">
                        <i class="bi bi-funnel-fill text-[10px]"></i>
                        {{ ucfirst(request('status')) }}
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

                    {{-- Filter Status --}}
                    <div class="flex flex-col gap-1 min-w-[160px]">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</label>
                        <div class="relative">
                            <select x-model="filterStatus"
                                class="w-full appearance-none bg-gray-100 rounded-xl px-3 py-2.5 text-sm text-gray-700 
                                focus:outline-none focus:ring-2 focus:ring-green-300 transition 
                                cursor-pointer pr-8">
                                <option value="">Semua Status</option>
                                <option value="diterima" {{ request('status') === 'diterima' ? 'selected' : '' }}>Diterima</option>
                                <option value="gagal"    {{ request('status') === 'gagal'    ? 'selected' : '' }}>Gagal</option>
                                <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
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
                    @if(request('search') || request('status') || request('date_from') || request('date_to'))
                    <a href="{{ route('riwayat') }}"
                       class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-red-500 bg-gray-100 hover:bg-red-50 px-3 py-2.5 rounded-xl transition whitespace-nowrap">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Reset Filter
                    </a>
                    @endif
                </div>
            </div>

            {{-- Result count --}}
            @if(request('search') || request('status') || request('date_from') || request('date_to'))
            <p class="text-sm text-gray-500 mb-3 px-1">
                Ditemukan <span class="font-semibold text-gray-700">{{ $riwayat->total() }}</span> riwayat
            </p>
            @endif

            {{-- Desktop Table --}}
            <div class="hidden md:block bg-white rounded-3xl shadow p-4 sm:p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-gray-500 uppercase text-xs border-b">
                            @if(auth()->user()->role !== 'penerima')
                            <tr>
                                <th class="py-4 pr-4">No</th>
                                <th class="pr-4">Tanggal</th>
                                <th class="pr-4">Menu</th>
                                <th class="pr-4">Kader</th>
                                <th class="pr-4">Jadwal</th>
                                <th class="pr-4">Status</th>
                                <th class="pr-4">Keterangan</th>
                                <th>Ulasan</th>
                            </tr>
                            @endif

                        </thead>
                        <tbody class="divide-y">
                            @forelse($riwayat as $i => $d)
                            @php
                                $statusStyle = match($d->status) {
                                    'diterima' => 'background:#D7F487;color:#4E6F5C',
                                    'gagal'    => 'background:#FEE2E2;color:#991B1B',
                                    default    => 'background:#FEF9C3;color:#854D0E',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-4 pr-4 text-gray-500 text-xs">{{ $riwayat->firstItem() + $i }}</td>
                                <td class="pr-4 whitespace-nowrap">
                                    <span class="font-medium text-gray-800">{{ $d->waktu_distribusi->format('d M Y') }}</span><br>
                                    <span class="text-xs text-gray-400">{{ $d->waktu_distribusi->format('H:i') }}</span>
                                </td>
                                <td class="pr-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold"
                                          style="background:#D7F487;color:#4E6F5C">
                                        {{ $d->menu->nama ?? '-' }}
                                    </span>
                                </td>
                                <td class="pr-4 text-gray-600 text-xs">{{ $d->kader->name ?? '-' }}</td>
                                <td class="pr-4 text-gray-400 text-xs">
                                    {{ $d->jadwal?->tanggal->format('d M Y') ?? '-' }}
                                </td>
                                <td class="pr-4">
                                    <span class="text-xs px-3 py-1 rounded-full font-semibold whitespace-nowrap"
                                          style="{{ $statusStyle }}">
                                        {{ strtoupper($d->status) }}
                                    </span>
                                </td>
                                <td class="pr-4 text-gray-500 text-xs max-w-[160px]">
                                    <span class="block truncate">{{ $d->keterangan ?? '-' }}</span>
                                </td>
                                <td>
                                    @if($d->status === 'diterima')
                                        @if($d->feedback)
                                        <a href="{{ route('feedback.show', $d->feedback->id) }}"
                                           class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-lg transition"
                                           style="background:#D7F487;color:#4E6F5C">
                                            <i class="bi bi-star-fill text-yellow-400 text-[10px]"></i>
                                            {{ $d->feedback->rating }}/5
                                        </a>
                                        @else
                                        <a href="{{ route('feedback.index') }}"
                                           class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-lg border border-gray-200 text-gray-400 hover:border-green-300 hover:text-green-600 transition">
                                            <i class="bi bi-star text-[10px]"></i>
                                            Beri Ulasan
                                        </a>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-12 text-gray-400">
                                    <i class="bi bi-clock-history text-4xl block mb-2"></i>
                                    Belum ada riwayat distribusi
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Mobile Cards --}}
            <div class="md:hidden space-y-3">
                @forelse($riwayat as $d)
                @php
                    $statusStyle = match($d->status) {
                        'diterima' => 'background:#D7F487;color:#4E6F5C',
                        'gagal'    => 'background:#FEE2E2;color:#991B1B',
                        default    => 'background:#FEF9C3;color:#854D0E',
                    };
                @endphp
                <div class="bg-white rounded-2xl shadow p-4">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $d->menu->nama ?? '-' }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $d->waktu_distribusi->format('d M Y, H:i') }}</p>
                        </div>
                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold whitespace-nowrap flex-shrink-0"
                              style="{{ $statusStyle }}">
                            {{ strtoupper($d->status) }}
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-3 text-xs">
                        <span class="px-2 py-1 rounded-lg bg-gray-100 text-gray-500">
                            <i class="bi bi-person-badge mr-1"></i>{{ $d->kader->name ?? '-' }}
                        </span>
                        @if($d->jadwal)
                        <span class="px-2 py-1 rounded-lg bg-gray-100 text-gray-500">
                            <i class="bi bi-calendar3 mr-1"></i>{{ $d->jadwal->tanggal->format('d M Y') }}
                        </span>
                        @endif
                    </div>

                    @if($d->keterangan)
                    <p class="text-xs text-gray-500 mb-3">{{ $d->keterangan }}</p>
                    @endif

                    @if($d->status === 'diterima')
                    <div class="pt-3 border-t border-gray-100">
                        @if($d->feedback)
                        <a href="{{ route('feedback.show', $d->feedback->id) }}"
                           class="flex items-center justify-center gap-2 w-full text-xs font-semibold py-2 rounded-lg transition"
                           style="background:#D7F487;color:#4E6F5C">
                            <i class="bi bi-star-fill text-yellow-400"></i>
                            Lihat Ulasan ({{ $d->feedback->rating }}/5)
                        </a>
                        @else
                        <a href="{{ route('feedback.index') }}"
                           class="flex items-center justify-center gap-2 w-full text-xs font-semibold py-2 rounded-lg border border-gray-200 text-gray-500 hover:border-green-300 hover:text-green-600 transition">
                            <i class="bi bi-star"></i>
                            Beri Ulasan
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
                @empty
                <div class="bg-white rounded-2xl shadow p-10 text-center text-gray-400">
                    <i class="bi bi-clock-history text-4xl block mb-2"></i>
                    Belum ada riwayat distribusi
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white rounded-2xl shadow px-5 py-4">
                <p class="text-sm text-gray-500 order-2 sm:order-1">
                    Menampilkan <span class="font-semibold text-gray-700">{{ $riwayat->count() }}</span>
                    dari <span class="font-semibold text-gray-700">{{ $riwayat->total() }}</span> riwayat
                </p>
                <div class="flex items-center gap-1 order-1 sm:order-2">
                    <span class="w-9 h-9 flex items-center justify-center rounded-xl {{ $riwayat->onFirstPage() ? 'text-gray-300 cursor-not-allowed' : 'text-gray-500' }}">
                        @if(!$riwayat->onFirstPage())
                            <a href="{{ $riwayat->previousPageUrl() }}" class="w-full h-full flex items-center justify-center hover:bg-gray-100 rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            </a>
                        @else
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        @endif
                    </span>
                    @foreach($riwayat->getUrlRange(max(1, $riwayat->currentPage() - 2), min($riwayat->lastPage(), $riwayat->currentPage() + 2)) as $page => $url)
                        @if($page == $riwayat->currentPage())
                            <span class="w-9 h-9 flex items-center justify-center rounded-xl text-white text-sm font-bold" style="background:#06B13D">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center rounded-xl text-gray-600 hover:bg-gray-100 text-sm transition">{{ $page }}</a>
                        @endif
                    @endforeach
                    <span class="w-9 h-9 flex items-center justify-center rounded-xl {{ $riwayat->hasMorePages() ? 'text-gray-500' : 'text-gray-300 cursor-not-allowed' }}">
                        @if($riwayat->hasMorePages())
                            <a href="{{ $riwayat->nextPageUrl() }}" class="w-full h-full flex items-center justify-center hover:bg-gray-100 rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @else
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        @endif
                    </span>
                </div>
            </div>

        </div>
    </main>
</div>
@endsection