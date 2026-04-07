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

            filterRt: '{{ request('rt') }}',
            filterDateFrom: '{{ request('date_from') }}',
            filterDateTo: '{{ request('date_to') }}',
            searchQuery: '{{ request('search') }}',

            submitSearch() {
                const params = new URLSearchParams();
                if (this.searchQuery.trim()) params.set('search', this.searchQuery.trim());
                if (this.filterRt)           params.set('rt', this.filterRt);
                if (this.filterDateFrom)     params.set('date_from', this.filterDateFrom);
                if (this.filterDateTo)       params.set('date_to', this.filterDateTo);
                window.location.href = '{{ route('jadwal.index') }}' + (params.toString() ? '?' + params.toString() : '');
            },
        }">

        {{-- Mobile Top Navbar --}}
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
            <div class="mb-4 flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-white"
                 style="background:#06B13D">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
            @endif

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 mb-6">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold" style="color:#4E6F5C">Jadwal Kader</h1>
                    <p class="text-gray-500 text-sm">Daftar jadwal kunjungan NutriBase per RT</p>
                </div>

                @can('create', App\Models\Jadwal::class)
                <button
                    @click="openModal = true"
                    class="self-start sm:self-auto font-medium px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl flex items-center gap-2 shadow-sm transition text-sm sm:text-base whitespace-nowrap text-white"
                    style="background:#06B13D">
                    <span class="bg-white/20 w-6 h-6 flex items-center justify-center rounded-full text-sm flex-shrink-0">+</span>
                    Tambah Jadwal
                </button>
                @endcan
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
                placeholder="Cari menu, kader, atau keterangan..."
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
            @if(request('rt') || request('date_from') || request('date_to'))
            <span class="absolute -top-1 -right-1 w-2.5 h-2.5 rounded-full border-2 border-white" style="background:#06B13D"></span>
            @endif
        </button>

        <button @click="submitSearch()"
            class="text-white px-5 py-2.5 rounded-xl text-sm font-medium transition whitespace-nowrap"
            style="background:#06B13D">Cari</button>
    </div>

    {{-- Active filter chips --}}
    @if(request('rt') || request('date_from') || request('date_to'))
    <div class="flex items-center gap-2 flex-wrap mt-2.5">
        @if(request('rt'))
        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full"
              style="background:#D7F487;color:#4E6F5C">
            <i class="bi bi-funnel-fill text-[10px]"></i>
            {{ request('rt') }}
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

        {{-- Filter RT --}}
        <div class="flex flex-col gap-1 min-w-[160px]">
            <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Filter RT</label>
            <div class="relative">
                <select x-model="filterRt" @change="submitSearch()"
                    class="w-full appearance-none bg-gray-100 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300 transition cursor-pointer pr-8">
                    <option value="">Semua RT</option>
                    @foreach($rtList as $rt)
                        <option value="{{ $rt }}" {{ request('rt') === $rt ? 'selected' : '' }}>{{ $rt }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-2.5 flex items-center">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
        </div>

        {{-- Filter Tanggal --}}
        <div class="flex flex-col gap-1 flex-1 min-w-[220px]">
            <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Rentang Tanggal</label>
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
        @if(request('search') || request('rt') || request('date_from') || request('date_to'))
        <a href="{{ route('jadwal.index') }}"
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
            @if(request('search') || request('rt'))
            <p class="text-sm text-gray-500 mb-3 px-1">
                Ditemukan <span class="font-semibold text-gray-700">{{ $jadwals->total() }}</span> jadwal
                @if(request('search')) untuk <span class="font-semibold" style="color:#06B13D">"{{ request('search') }}"</span>@endif
                @if(request('rt')) di <span class="font-semibold" style="color:#4E6F5C">{{ request('rt') }}</span>@endif
            </p>
            @endif

            {{-- Desktop Table --}}
            <div class="hidden md:block bg-white rounded-3xl shadow p-4 sm:p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-gray-500 uppercase text-xs border-b">
                            <tr>
                                <th class="py-4 pr-4">No</th>
                                <th class="pr-4">Tanggal</th>
                                <th class="pr-4">Menu</th>
                                <th class="pr-4">Kader</th>
                                <th class="pr-4">RT</th>
                                <th class="pr-4">Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($jadwals as $i => $j)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-4 pr-4 text-gray-500">{{ $jadwals->firstItem() + $i }}</td>
                                <td class="pr-4">
                                    <span class="font-medium text-gray-800">{{ $j->tanggal->format('d M Y') }}</span>
                                </td>
                                <td class="pr-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold"
                                          style="background:#D7F487;color:#4E6F5C">
                                        {{ $j->menu->nama ?? '-' }}
                                    </span>
                                </td>
                                <td class="pr-4 font-medium text-gray-700">{{ $j->kader->name ?? '-' }}</td>
                                <td class="pr-4">
                                    <span class="font-mono text-xs px-2 py-1 rounded-lg bg-gray-100 text-gray-600">{{ $j->rt }}</span>
                                </td>
                                <td class="pr-4 text-gray-500 text-xs max-w-[200px]">
                                    <span class="block truncate">{{ $j->keterangan ?? '-' }}</span>
                                </td>
                                <td class="space-x-3 text-xs font-semibold tracking-wide">
                                    @can('update', $j)
                                    <button
                                        @click="
                                            editData = {
                                                id: {{ $j->id }},
                                                menu_id: {{ $j->menu_id }},
                                                tanggal: '{{ $j->tanggal->format('Y-m-d') }}',
                                                rt: '{{ $j->rt }}',
                                                keterangan: @js($j->keterangan),
                                            };
                                            editModal = true
                                        "
                                        class="transition hover:opacity-70"
                                        style="color:#06B13D">EDIT</button>
                                    @endcan

                                    @can('delete', $j)
                                    <form id="del-{{ $j->id }}" action="{{ route('jadwal.destroy', $j->id) }}" method="POST" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button type="button"
                                        @click="
                                            Swal.fire({
                                                title: 'Hapus jadwal ini?',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#06B13D',
                                                cancelButtonColor: '#d33',
                                                confirmButtonText: 'Ya, hapus!'
                                            }).then(r => { if (r.isConfirmed) document.getElementById('del-{{ $j->id }}').submit() })
                                        "
                                        class="text-red-500 hover:text-red-700 transition">HAPUS</button>
                                    @endcan
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-gray-400">
                                    <i class="bi bi-calendar-x text-4xl block mb-2"></i>
                                    Belum ada data jadwal
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Mobile Card --}}
            <div class="md:hidden space-y-3">
                @forelse($jadwals as $j)
                <div class="bg-white rounded-2xl shadow p-4">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800">{{ $j->tanggal->format('d M Y') }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $j->kader->name ?? '-' }}</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold flex-shrink-0"
                              style="background:#D7F487;color:#4E6F5C">
                            {{ $j->menu->nama ?? '-' }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2 mb-3">
                        <span class="font-mono text-xs px-2 py-1 rounded-lg bg-gray-100 text-gray-600">{{ $j->rt }}</span>
                        @if($j->keterangan)
                        <span class="text-xs text-gray-500 truncate">{{ $j->keterangan }}</span>
                        @endif
                    </div>

                    <div class="flex gap-2 pt-3 border-t border-gray-100">
                        @can('update', $j)
                        <button
                            @click="
                                editData = {
                                    id: {{ $j->id }},
                                    menu_id: {{ $j->menu_id }},
                                    tanggal: '{{ $j->tanggal->format('Y-m-d') }}',
                                    rt: '{{ $j->rt }}',
                                    keterangan: @js($j->keterangan),
                                };
                                editModal = true
                            "
                            class="flex-1 text-xs font-semibold py-2 rounded-lg transition text-white"
                            style="background:#06B13D">
                            EDIT
                        </button>
                        @endcan

                        @can('delete', $j)
                        <button type="button"
                            @click="
                                Swal.fire({
                                    title: 'Hapus jadwal ini?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#06B13D',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ya, hapus!'
                                }).then(r => { if (r.isConfirmed) document.getElementById('del-{{ $j->id }}').submit() })
                            "
                            class="flex-1 text-xs font-semibold text-red-500 bg-red-50 hover:bg-red-100 py-2 rounded-lg transition">
                            HAPUS
                        </button>
                        @endcan
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-2xl shadow p-10 text-center text-gray-400">
                    <i class="bi bi-calendar-x text-4xl block mb-2"></i>
                    Belum ada data jadwal
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white rounded-2xl shadow px-5 py-4">
                <p class="text-sm text-gray-500 order-2 sm:order-1">
                    Menampilkan <span class="font-semibold text-gray-700">{{ $jadwals->count() }}</span>
                    dari <span class="font-semibold text-gray-700">{{ $jadwals->total() }}</span> jadwal
                </p>

                <div class="flex items-center gap-1 order-1 sm:order-2">
                    <span class="w-9 h-9 flex items-center justify-center rounded-xl {{ $jadwals->onFirstPage() ? 'text-gray-300 cursor-not-allowed' : 'text-gray-500 hover:text-white cursor-pointer transition' }}"
                          @if(!$jadwals->onFirstPage()) style="hover:background:#06B13D" @endif>
                        @if(!$jadwals->onFirstPage())
                            <a href="{{ $jadwals->previousPageUrl() }}" class="w-full h-full flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            </a>
                        @else
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        @endif
                    </span>

                    @foreach($jadwals->getUrlRange(max(1, $jadwals->currentPage() - 2), min($jadwals->lastPage(), $jadwals->currentPage() + 2)) as $page => $url)
                        @if($page == $jadwals->currentPage())
                            <span class="w-9 h-9 flex items-center justify-center rounded-xl text-white text-sm font-bold" style="background:#06B13D">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center rounded-xl text-gray-600 hover:bg-gray-100 text-sm transition">{{ $page }}</a>
                        @endif
                    @endforeach

                    <span class="w-9 h-9 flex items-center justify-center rounded-xl {{ $jadwals->hasMorePages() ? 'text-gray-500 cursor-pointer' : 'text-gray-300 cursor-not-allowed' }}">
                        @if($jadwals->hasMorePages())
                            <a href="{{ $jadwals->nextPageUrl() }}" class="w-full h-full flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @else
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        @endif
                    </span>
                </div>
            </div>

        </div>

        @can('create', App\Models\Jadwal::class)
            @include('jadwal.form')
        @endcan

        @can('updateAny', App\Models\Jadwal::class)
            @include('jadwal.form')
        @endcan

    </main>
</div>
@endsection