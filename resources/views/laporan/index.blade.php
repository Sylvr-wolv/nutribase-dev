@extends('layouts.app')

@section('content')
<div class="flex min-h-screen" style="background:#FAFCFB">

    @include('layouts.sidebar')

    <main
        class="flex-1 flex flex-col min-w-0 lg:pl-72"
        x-data="{
            downloadModal: false,
            downloadFormat: 'pdf',
            dateFrom: '{{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('Y-m-d') : '' }}',
            dateTo: '{{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('Y-m-d') : '' }}',
        
            submitFilter() {
                const params = new URLSearchParams();
                if (this.dateFrom) params.set('date_from', this.dateFrom);
                if (this.dateTo)   params.set('date_to', this.dateTo);
                window.location.href = '{{ route('laporan.index') }}' + (params.toString() ? '?' + params.toString() : '');
            },
        
            triggerDownload() {
                const params = new URLSearchParams();
                params.set('format', this.downloadFormat);
                if (this.dateFrom) params.set('date_from', this.dateFrom);
                if (this.dateTo)   params.set('date_to', this.dateTo);
                window.location.href = '{{ route('laporan.download') }}?' + params.toString();
                this.downloadModal = false;
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
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 mb-6">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold" style="color:#4E6F5C">Laporan Terpadu</h1>
                    <p class="text-gray-500 text-sm">Data distribusi dan penerima dalam satu laporan</p>
                </div>
                <button
                    @click="downloadModal = true"
                    class="self-start sm:self-auto font-medium px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl flex items-center gap-2 shadow-sm transition text-sm sm:text-base whitespace-nowrap text-white"
                    style="background:#06B13D">
                    <i class="bi bi-download"></i>
                    Unduh Laporan
                </button>
            </div>

            {{-- Stats --}}
            @php
                $diterima = $distribusiData->where('status', 'diterima')->count();
                $gagal    = $distribusiData->where('status', 'gagal')->count();
                $pending  = $distribusiData->where('status', 'pending')->count();
            @endphp
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Total Distribusi</span>
                    <span class="text-2xl font-black" style="color:#4E6F5C">{{ $distribusiData->count() }}</span>
                </div>
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Diterima</span>
                    <span class="text-2xl font-black" style="color:#06B13D">{{ $diterima }}</span>
                </div>
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Gagal / Pending</span>
                    <span class="text-2xl font-black text-red-500">{{ $gagal + $pending }}</span>
                </div>
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Total Penerima</span>
                    <span class="text-2xl font-black" style="color:#4E6F5C">{{ $penerimaData->count() }}</span>
                </div>
            </div>

            {{-- Periode Filter --}}
            <div class="bg-white rounded-2xl shadow px-4 sm:px-6 py-4 mb-5">
                <div class="flex flex-col sm:flex-row gap-3 items-end flex-wrap">
                    <div class="flex flex-col gap-1 flex-1 min-w-[220px]">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Filter Periode</label>
                        <div class="flex items-center gap-2">
                            <input type="text" x-model="dateFrom"
    x-init="flatpickr($el, {
        locale: 'id',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd M Y',
        defaultDate: dateFrom || null,
        onChange: (d, str) => { dateFrom = str }
    })"
    placeholder="Pilih tanggal"
    class="flex-1 bg-gray-100 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300 transition">

<input type="text" x-model="dateTo"
    x-init="flatpickr($el, {
        locale: 'id',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd M Y',
        defaultDate: dateTo || null,
        onChange: (d, str) => { dateTo = str }
    })"
    placeholder="Pilih tanggal"
    class="flex-1 bg-gray-100 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300 transition">
                        </div>
                    </div>
                    <div class="flex gap-2 self-end">
                        <button @click="submitFilter()"
                            class="text-white px-5 py-2.5 rounded-xl text-sm font-medium transition whitespace-nowrap"
                            style="background:#06B13D">
                            Terapkan
                        </button>
                        @if(request('date_from') || request('date_to'))
                        <a href="{{ route('laporan.index') }}"
                           class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-red-500 bg-gray-100 hover:bg-red-50 px-4 py-2.5 rounded-xl transition whitespace-nowrap">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reset
                        </a>
                        @endif
                    </div>
                </div>

                @if(request('date_from') || request('date_to'))
                <div class="flex items-center gap-2 mt-3">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full"
                          style="background:#D7F487;color:#4E6F5C">
                        <i class="bi bi-calendar3 text-[10px]"></i>
                        {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('d M Y') : '...' }}
                        –
                        {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('d M Y') : '...' }}
                    </span>
                </div>
                @endif
            </div>

            {{-- ══ DISTRIBUSI TABLE ══ --}}
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-base font-bold" style="color:#4E6F5C">
                        <i class="bi bi-truck mr-1.5"></i> Data Distribusi
                        <span class="ml-2 text-xs font-semibold px-2 py-0.5 rounded-full" style="background:#D7F487;color:#4E6F5C">
                            {{ $distribusiData->count() }} data
                        </span>
                    </h2>
                </div>

                {{-- Desktop --}}
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
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @forelse($distribusiData as $i => $d)
                                @php
                                    $statusStyle = match($d->status) {
                                        'diterima' => 'background:#D7F487;color:#4E6F5C',
                                        'gagal'    => 'background:#FEE2E2;color:#991B1B',
                                        default    => 'background:#FEF9C3;color:#854D0E',
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-3 pr-4 text-gray-500 text-xs">{{ $i + 1 }}</td>
                                    <td class="pr-4 whitespace-nowrap">
                                        <span class="font-medium text-gray-800 text-sm">{{ $d->waktu_distribusi->format('d M Y') }}</span><br>
                                        <span class="text-xs text-gray-400">{{ $d->waktu_distribusi->format('H:i') }}</span>
                                    </td>
                                    <td class="pr-4 font-medium text-gray-700">{{ $d->penerima->nama ?? '-' }}</td>
                                    <td class="pr-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold"
                                              style="background:#D7F487;color:#4E6F5C">
                                            {{ $d->menu->nama ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="pr-4 text-gray-600 text-xs">{{ $d->kader->name ?? '-' }}</td>
                                    <td class="pr-4 text-gray-400 text-xs">{{ $d->jadwal?->tanggal->format('d M Y') ?? '-' }}</td>
                                    <td class="pr-4">
                                        <span class="text-xs px-3 py-1 rounded-full font-semibold whitespace-nowrap"
                                              style="{{ $statusStyle }}">
                                            {{ strtoupper($d->status) }}
                                        </span>
                                    </td>
                                    <td class="text-gray-500 text-xs max-w-[180px]">
                                        <span class="block truncate">{{ $d->keterangan ?? '-' }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-10 text-gray-400">
                                        <i class="bi bi-box-seam text-3xl block mb-2"></i>
                                        Tidak ada data distribusi pada periode ini
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Mobile --}}
                <div class="md:hidden space-y-3">
                    @forelse($distribusiData as $d)
                    @php
                        $statusStyle = match($d->status) {
                            'diterima' => 'background:#D7F487;color:#4E6F5C',
                            'gagal'    => 'background:#FEE2E2;color:#991B1B',
                            default    => 'background:#FEF9C3;color:#854D0E',
                        };
                    @endphp
                    <div class="bg-white rounded-2xl shadow p-4">
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $d->penerima->nama ?? '-' }}</p>
                                <p class="text-xs text-gray-400">{{ $d->waktu_distribusi->format('d M Y, H:i') }}</p>
                            </div>
                            <span class="text-xs px-2.5 py-1 rounded-full font-semibold whitespace-nowrap flex-shrink-0"
                                  style="{{ $statusStyle }}">
                                {{ strtoupper($d->status) }}
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <span class="px-2 py-1 rounded-lg font-semibold" style="background:#D7F487;color:#4E6F5C">{{ $d->menu->nama ?? '-' }}</span>
                            <span class="px-2 py-1 rounded-lg bg-gray-100 text-gray-500">{{ $d->kader->name ?? '-' }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-2xl shadow p-10 text-center text-gray-400">
                        <i class="bi bi-box-seam text-3xl block mb-2"></i>
                        Tidak ada data distribusi
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- ══ PENERIMA TABLE ══ --}}
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-base font-bold" style="color:#4E6F5C">
                        <i class="bi bi-people-fill mr-1.5"></i> Data Penerima
                        <span class="ml-2 text-xs font-semibold px-2 py-0.5 rounded-full" style="background:#D7F487;color:#4E6F5C">
                            {{ $penerimaData->count() }} data
                        </span>
                    </h2>
                </div>

                {{-- Desktop --}}
                <div class="hidden md:block bg-white rounded-3xl shadow p-4 sm:p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-gray-500 uppercase text-xs border-b">
                                <tr>
                                    <th class="py-4 pr-4">No</th>
                                    <th class="pr-4">Nama</th>
                                    <th class="pr-4">NIK</th>
                                    <th class="pr-4">RT</th>
                                    <th class="pr-4">Kategori</th>
                                    <th class="pr-4">Est. Selesai</th>
                                    <th>Terdaftar</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @forelse($penerimaData as $i => $p)
                                @php
                                    $katStyle = match($p->kategori) {
                                        'ibu_hamil'    => 'background:#FEF3C7;color:#92400E',
                                        'ibu_menyusui' => 'background:#DBEAFE;color:#1E40AF',
                                        'balita'       => 'background:#D7F487;color:#3A5C0D',
                                        default        => 'background:#F3F4F6;color:#4B5563',
                                    };
                                    $katLabel = match($p->kategori) {
                                        'ibu_hamil'    => 'Ibu Hamil',
                                        'ibu_menyusui' => 'Ibu Menyusui',
                                        'balita'       => 'Balita',
                                        default        => 'Lainnya',
                                    };
                                    $daysLeft = now()->diffInDays($p->estimasi_durasi, false);
                                    $estColor = $daysLeft > 30 ? 'color:#06B13D' : ($daysLeft > 0 ? 'color:#D97706' : 'color:#DC2626');
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-3 pr-4 text-gray-500 text-xs">{{ $i + 1 }}</td>
                                    <td class="pr-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0"
                                                 style="background:#4E6F5C;color:#D7F487">
                                                {{ strtoupper(substr($p->user->name ?? '-', 0, 1)) }}
                                            </div>
                                            <span class="font-medium text-gray-700">{{ $p->user->name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="pr-4 font-mono text-xs text-gray-500 tracking-wider">{{ $p->nik }}</td>
                                    <td class="pr-4 text-gray-600 text-sm font-medium">RT {{ $p->rt }}</td>
                                    <td class="pr-4">
                                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold whitespace-nowrap"
                                              style="{{ $katStyle }}">
                                            {{ $katLabel }}
                                        </span>
                                    </td>
                                    <td class="pr-4 text-xs font-medium" style="{{ $estColor }}">
                                        {{ $p->estimasi_durasi->format('d M Y') }}
                                    </td>
                                    <td class="text-xs text-gray-400">{{ $p->created_at->format('d M Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-10 text-gray-400">
                                        <i class="bi bi-people text-3xl block mb-2"></i>
                                        Tidak ada data penerima pada periode ini
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Mobile --}}
                <div class="md:hidden space-y-3">
                    @forelse($penerimaData as $p)
                    @php
                        $katStyle = match($p->kategori) {
                            'ibu_hamil'    => 'background:#FEF3C7;color:#92400E',
                            'ibu_menyusui' => 'background:#DBEAFE;color:#1E40AF',
                            'balita'       => 'background:#D7F487;color:#3A5C0D',
                            default        => 'background:#F3F4F6;color:#4B5563',
                        };
                        $katLabel = match($p->kategori) {
                            'ibu_hamil'    => 'Ibu Hamil',
                            'ibu_menyusui' => 'Ibu Menyusui',
                            'balita'       => 'Balita',
                            default        => 'Lainnya',
                        };
                    @endphp
                    <div class="bg-white rounded-2xl shadow p-4">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold flex-shrink-0"
                                 style="background:#4E6F5C;color:#D7F487">
                                {{ strtoupper(substr($p->user->name ?? '-', 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800">{{ $p->user->name ?? '-' }}</p>
                                <p class="text-xs font-mono text-gray-400">{{ $p->nik }}</p>
                            </div>
                            <span class="text-xs px-2.5 py-1 rounded-full font-semibold flex-shrink-0"
                                  style="{{ $katStyle }}">{{ $katLabel }}</span>
                        </div>
                        <div class="flex gap-3 text-xs text-gray-500">
                            <span>RT {{ $p->rt }}</span>
                            <span>·</span>
                            <span>Est. {{ $p->estimasi_durasi->format('d M Y') }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-2xl shadow p-10 text-center text-gray-400">
                        <i class="bi bi-people text-3xl block mb-2"></i>
                        Tidak ada data penerima
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ══════ DOWNLOAD MODAL ══════ --}}
        <div x-show="downloadModal"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="display:none">

            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="downloadModal = false"></div>

            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm z-10 overflow-hidden"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between" style="background:#06B13D">
                    <h2 class="text-base font-bold text-white">Unduh Laporan</h2>
                    <button @click="downloadModal = false" class="text-white/70 hover:text-white transition">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">

                    {{-- Periode info --}}
                    @if(request('date_from') || request('date_to'))
                    <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-xs font-medium" style="background:#D7F487;color:#4E6F5C">
                        <i class="bi bi-calendar3"></i>
                        {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('d M Y') : 'Awal' }}
                        –
                        {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('d M Y') : 'Sekarang' }}
                    </div>
                    @else
                    <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-xs font-medium bg-gray-100 text-gray-500">
                        <i class="bi bi-calendar3"></i>
                        Semua periode
                    </div>
                    @endif

                    {{-- Format picker --}}
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Pilih Format</p>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="cursor-pointer">
                                <input type="radio" name="dl_format" value="pdf" x-model="downloadFormat" class="sr-only">
                                <div class="flex flex-col items-center gap-1.5 py-3 rounded-xl border-2 transition text-center"
                                     :class="downloadFormat === 'pdf'
                                        ? 'border-red-400 bg-red-50'
                                        : 'border-gray-200 bg-gray-50 hover:border-red-200'">
                                    <i class="bi bi-file-earmark-pdf-fill text-xl"
                                       :class="downloadFormat === 'pdf' ? 'text-red-500' : 'text-gray-400'"></i>
                                    <span class="text-xs font-bold"
                                          :class="downloadFormat === 'pdf' ? 'text-red-600' : 'text-gray-500'">PDF</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="dl_format" value="xlsx" x-model="downloadFormat" class="sr-only">
                                <div class="flex flex-col items-center gap-1.5 py-3 rounded-xl border-2 transition text-center"
                                     :class="downloadFormat === 'xlsx'
                                        ? 'border-green-400 bg-green-50'
                                        : 'border-gray-200 bg-gray-50 hover:border-green-200'">
                                    <i class="bi bi-file-earmark-excel-fill text-xl"
                                       :class="downloadFormat === 'xlsx' ? 'text-green-600' : 'text-gray-400'"></i>
                                    <span class="text-xs font-bold"
                                          :class="downloadFormat === 'xlsx' ? 'text-green-700' : 'text-gray-500'">Excel</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="dl_format" value="csv" x-model="downloadFormat" class="sr-only">
                                <div class="flex flex-col items-center gap-1.5 py-3 rounded-xl border-2 transition text-center"
                                     :class="downloadFormat === 'csv'
                                        ? 'border-blue-400 bg-blue-50'
                                        : 'border-gray-200 bg-gray-50 hover:border-blue-200'">
                                    <i class="bi bi-file-earmark-text-fill text-xl"
                                       :class="downloadFormat === 'csv' ? 'text-blue-500' : 'text-gray-400'"></i>
                                    <span class="text-xs font-bold"
                                          :class="downloadFormat === 'csv' ? 'text-blue-600' : 'text-gray-500'">CSV</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Format description --}}
                    <p class="text-xs text-gray-400 text-center min-h-[16px]">
                        <span x-show="downloadFormat === 'pdf'">Dokumen resmi siap cetak, landscape A4</span>
                        <span x-show="downloadFormat === 'xlsx'">2 sheet: Distribusi & Penerima, format Excel</span>
                        <span x-show="downloadFormat === 'csv'">2 file dalam format teks, cocok untuk import data</span>
                    </p>

                    <div class="flex gap-3 pt-1">
                        <button type="button" @click="downloadModal = false"
                            class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                            Batal
                        </button>
                        <button type="button" @click="triggerDownload()"
                            class="flex-1 py-2.5 rounded-xl text-sm font-bold text-white transition flex items-center justify-center gap-2"
                            style="background:#06B13D">
                            <i class="bi bi-download"></i>
                            Unduh
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>
@endsection