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

            filterStatus: '{{ request('status') }}',
            filterDateFrom: '{{ request('date_from') }}',
            filterDateTo: '{{ request('date_to') }}',
            searchQuery: '{{ request('search') }}',

            submitSearch() {
                const params = new URLSearchParams();
                if (this.searchQuery.trim()) params.set('search', this.searchQuery.trim());
                if (this.filterStatus)       params.set('status', this.filterStatus);
                if (this.filterDateFrom)     params.set('date_from', this.filterDateFrom);
                if (this.filterDateTo)       params.set('date_to', this.filterDateTo);
                window.location.href = '{{ route('distribusi.index') }}' + (params.toString() ? '?' + params.toString() : '');
            },

            statusColor(status) {
                if (status === 'diterima') return 'background:#D7F487;color:#4E6F5C';
                if (status === 'gagal')    return 'background:#FEE2E2;color:#991B1B';
                return 'background:#FEF9C3;color:#854D0E';
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
                    <h1 class="text-xl sm:text-2xl font-bold" style="color:#4E6F5C">Data Distribusi</h1>
                    <p class="text-gray-500 text-sm">Riwayat distribusi makanan kepada penerima</p>
                </div>

                @can('create', App\Models\Distribusi::class)
                <button
                    @click="openModal = true"
                    class="self-start sm:self-auto font-medium px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl flex items-center gap-2 shadow-sm transition text-sm sm:text-base whitespace-nowrap text-white"
                    style="background:#06B13D">
                    <span class="bg-white/20 w-6 h-6 flex items-center justify-center rounded-full text-sm flex-shrink-0">+</span>
                    Tambah Distribusi
                </button>
                @endcan
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-3 gap-3 mb-5">
                @php
                    $total    = $distribusiStats['total']    ?? 0;
                    $diterima = $distribusiStats['diterima'] ?? 0;
                    $gagal    = $distribusiStats['gagal']    ?? 0;
                    $pending  = $distribusiStats['pending']  ?? 0;
                @endphp
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Total</span>
                    <span class="text-2xl font-black" style="color:#4E6F5C">{{ $total }}</span>
                </div>
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Diterima</span>
                    <span class="text-2xl font-black" style="color:#06B13D">{{ $diterima }}</span>
                </div>
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Gagal/Pending</span>
                    <span class="text-2xl font-black text-red-500">{{ $gagal + $pending }}</span>
                </div>
            </div>

            {{-- Search + Filter --}}
            <div class="bg-white rounded-2xl shadow px-4 sm:px-6 py-4 mb-4 flex flex-col gap-3">
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                            </svg>
                        </div>
                        <input type="text" x-model="searchQuery" @keydown.enter="submitSearch()"
                            placeholder="Cari nama penerima, menu, atau kader..."
                            class="w-full bg-gray-100 rounded-xl pl-9 pr-9 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300 transition">
                        <button x-show="searchQuery" @click="searchQuery = ''; submitSearch()"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <button @click="submitSearch()"
                        class="text-white px-5 py-2.5 rounded-xl text-sm font-medium transition whitespace-nowrap"
                        style="background:#06B13D">
                        Cari
                    </button>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 flex-wrap items-end">
                    {{-- Filter Status --}}
                    <div class="flex flex-col gap-1 min-w-[160px]">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</label>
                        <div class="relative">
                            <select x-model="filterStatus" @change="submitSearch()"
                                class="w-full appearance-none bg-gray-100 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300 transition cursor-pointer pr-8">
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
                    <div class="flex flex-col gap-1 min-w-[220px]">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Rentang Waktu</label>
                        <div class="flex items-center gap-2">
                            <input type="date" x-model="filterDateFrom" @change="submitSearch()"
                                class="flex-1 bg-gray-100 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300 transition">
                            <span class="text-gray-400 text-xs font-medium flex-shrink-0">s/d</span>
                            <input type="date" x-model="filterDateTo" @change="submitSearch()"
                                class="flex-1 bg-gray-100 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300 transition">
                        </div>
                    </div>

                    {{-- Active chips + Reset --}}
                    <div class="flex items-center gap-2 flex-wrap self-end">
                        @if(request('search'))
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full"
                              style="background:#D7F487;color:#4E6F5C">
                            "{{ request('search') }}"
                        </span>
                        @endif
                        @if(request('status'))
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full"
                              style="background:#D7F487;color:#4E6F5C">
                            {{ ucfirst(request('status')) }}
                        </span>
                        @endif
                        @if(request('search') || request('status') || request('date_from') || request('date_to'))
                        <a href="{{ route('distribusi.index') }}"
                           class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-red-500 bg-gray-100 hover:bg-red-50 px-3 py-2 rounded-xl transition whitespace-nowrap">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reset
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Result count --}}
            @if(request('search') || request('status'))
            <p class="text-sm text-gray-500 mb-3 px-1">
                Ditemukan <span class="font-semibold text-gray-700">{{ $distribusis->total() }}</span> data
                @if(request('search')) untuk <span class="font-semibold" style="color:#06B13D">"{{ request('search') }}"</span>@endif
                @if(request('status')) dengan status <span class="font-semibold" style="color:#4E6F5C">{{ ucfirst(request('status')) }}</span>@endif
            </p>
            @endif

            {{-- Desktop Table --}}
            <div class="hidden md:block bg-white rounded-3xl shadow p-4 sm:p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-gray-500 uppercase text-xs border-b">
                            <tr>
                                <th class="py-4 pr-4">No</th>
                                <th class="pr-4">Waktu</th>
                                <th class="pr-4">Penerima</th>
                                <th class="pr-4">Menu</th>
                                <th class="pr-4">Kader</th>
                                <th class="pr-4">Jadwal</th>
                                <th class="pr-4">Status</th>
                                <th class="pr-4">Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($distribusis as $i => $d)
                            @php
                                $statusStyle = match($d->status) {
                                    'diterima' => 'background:#D7F487;color:#4E6F5C',
                                    'gagal'    => 'background:#FEE2E2;color:#991B1B',
                                    default    => 'background:#FEF9C3;color:#854D0E',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-4 pr-4 text-gray-500">{{ $distribusis->firstItem() + $i }}</td>
                                <td class="pr-4 whitespace-nowrap">
                                    <span class="font-medium text-gray-800">{{ $d->waktu_distribusi->format('d M Y') }}</span><br>
                                    <span class="text-xs text-gray-400">{{ $d->waktu_distribusi->format('H:i') }}</span>
                                </td>
                                <td class="pr-4">
                                    <span class="font-medium text-gray-700">{{ $d->penerima->nama ?? '-' }}</span>
                                </td>
                                <td class="pr-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold"
                                          style="background:#D7F487;color:#4E6F5C">
                                        {{ $d->menu->nama ?? '-' }}
                                    </span>
                                </td>
                                <td class="pr-4 text-gray-600 text-xs">{{ $d->kader->name ?? '-' }}</td>
                                <td class="pr-4 text-gray-400 text-xs">
                                    {{ $d->jadwal ? $d->jadwal->tanggal->format('d M Y') : '-' }}
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
                                <td class="space-x-3 text-xs font-semibold tracking-wide whitespace-nowrap">
                                    @can('update', $d)
                                    <button
                                        @click="
                                            editData = {
                                                id: {{ $d->id }},
                                                jadwal_id: {{ $d->jadwal_id ?? 'null' }},
                                                penerima_id: {{ $d->penerima_id }},
                                                menu_id: {{ $d->menu_id }},
                                                waktu_distribusi: '{{ $d->waktu_distribusi->format('Y-m-d\TH:i') }}',
                                                status: '{{ $d->status }}',
                                                keterangan: @js($d->keterangan),
                                            };
                                            editModal = true
                                        "
                                        class="transition hover:opacity-70"
                                        style="color:#06B13D">EDIT</button>
                                    @endcan

                                    @can('delete', $d)
                                    <form id="del-{{ $d->id }}" action="{{ route('distribusi.destroy', $d->id) }}" method="POST" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button type="button"
                                        @click="
                                            Swal.fire({
                                                title: 'Hapus data distribusi ini?',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#06B13D',
                                                cancelButtonColor: '#d33',
                                                confirmButtonText: 'Ya, hapus!'
                                            }).then(r => { if (r.isConfirmed) document.getElementById('del-{{ $d->id }}').submit() })
                                        "
                                        class="text-red-500 hover:text-red-700 transition">HAPUS</button>
                                    @endcan
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-12 text-gray-400">
                                    <i class="bi bi-box-seam text-4xl block mb-2"></i>
                                    Belum ada data distribusi
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Mobile Cards --}}
            <div class="md:hidden space-y-3">
                @forelse($distribusis as $d)
                @php
                    $statusStyle = match($d->status) {
                        'diterima' => 'background:#D7F487;color:#4E6F5C',
                        'gagal'    => 'background:#FEE2E2;color:#991B1B',
                        default    => 'background:#FEF9C3;color:#854D0E',
                    };
                @endphp
                <div class="bg-white rounded-2xl shadow p-4">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800">{{ $d->penerima->nama ?? '-' }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $d->waktu_distribusi->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold whitespace-nowrap flex-shrink-0"
                              style="{{ $statusStyle }}">
                            {{ strtoupper($d->status) }}
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-3">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold"
                              style="background:#D7F487;color:#4E6F5C">
                            {{ $d->menu->nama ?? '-' }}
                        </span>
                        <span class="text-xs px-2 py-1 rounded-lg bg-gray-100 text-gray-500">
                            Kader: {{ $d->kader->name ?? '-' }}
                        </span>
                        @if($d->jadwal)
                        <span class="text-xs px-2 py-1 rounded-lg bg-gray-100 text-gray-500">
                            Jadwal: {{ $d->jadwal->tanggal->format('d M Y') }}
                        </span>
                        @endif
                    </div>

                    @if($d->keterangan)
                    <p class="text-xs text-gray-500 mb-3 truncate">{{ $d->keterangan }}</p>
                    @endif

                    <div class="flex gap-2 pt-3 border-t border-gray-100">
                        @can('update', $d)
                        <button
                            @click="
                                editData = {
                                    id: {{ $d->id }},
                                    jadwal_id: {{ $d->jadwal_id ?? 'null' }},
                                    penerima_id: {{ $d->penerima_id }},
                                    menu_id: {{ $d->menu_id }},
                                    waktu_distribusi: '{{ $d->waktu_distribusi->format('Y-m-d\TH:i') }}',
                                    status: '{{ $d->status }}',
                                    keterangan: @js($d->keterangan),
                                };
                                editModal = true
                            "
                            class="flex-1 text-xs font-semibold py-2 rounded-lg transition text-white"
                            style="background:#06B13D">
                            EDIT
                        </button>
                        @endcan

                        @can('delete', $d)
                        <button type="button"
                            @click="
                                Swal.fire({
                                    title: 'Hapus data distribusi ini?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#06B13D',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ya, hapus!'
                                }).then(r => { if (r.isConfirmed) document.getElementById('del-{{ $d->id }}').submit() })
                            "
                            class="flex-1 text-xs font-semibold text-red-500 bg-red-50 hover:bg-red-100 py-2 rounded-lg transition">
                            HAPUS
                        </button>
                        @endcan
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-2xl shadow p-10 text-center text-gray-400">
                    <i class="bi bi-box-seam text-4xl block mb-2"></i>
                    Belum ada data distribusi
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white rounded-2xl shadow px-5 py-4">
                <p class="text-sm text-gray-500 order-2 sm:order-1">
                    Menampilkan <span class="font-semibold text-gray-700">{{ $distribusis->count() }}</span>
                    dari <span class="font-semibold text-gray-700">{{ $distribusis->total() }}</span> data
                </p>

                <div class="flex items-center gap-1 order-1 sm:order-2">
                    <span class="w-9 h-9 flex items-center justify-center rounded-xl {{ $distribusis->onFirstPage() ? 'text-gray-300 cursor-not-allowed' : 'text-gray-500 cursor-pointer' }}">
                        @if(!$distribusis->onFirstPage())
                            <a href="{{ $distribusis->previousPageUrl() }}" class="w-full h-full flex items-center justify-center hover:bg-gray-100 rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            </a>
                        @else
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        @endif
                    </span>

                    @foreach($distribusis->getUrlRange(max(1, $distribusis->currentPage() - 2), min($distribusis->lastPage(), $distribusis->currentPage() + 2)) as $page => $url)
                        @if($page == $distribusis->currentPage())
                            <span class="w-9 h-9 flex items-center justify-center rounded-xl text-white text-sm font-bold" style="background:#06B13D">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center rounded-xl text-gray-600 hover:bg-gray-100 text-sm transition">{{ $page }}</a>
                        @endif
                    @endforeach

                    <span class="w-9 h-9 flex items-center justify-center rounded-xl {{ $distribusis->hasMorePages() ? 'text-gray-500 cursor-pointer' : 'text-gray-300 cursor-not-allowed' }}">
                        @if($distribusis->hasMorePages())
                            <a href="{{ $distribusis->nextPageUrl() }}" class="w-full h-full flex items-center justify-center hover:bg-gray-100 rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @else
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        @endif
                    </span>
                </div>
            </div>

        </div>

        @can('create', App\Models\Distribusi::class)
            @include('distribusi.form')
        @endcan

    </main>
</div>
@endsection