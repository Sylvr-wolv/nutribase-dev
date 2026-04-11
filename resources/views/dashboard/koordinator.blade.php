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

        <div class="flex-1 p-4 sm:p-6 lg:p-8">

            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-xl sm:text-2xl font-bold" style="color:#4E6F5C">
                    Dashboard Koordinator
                </h1>
                <p class="text-gray-500 text-sm mt-1">Pantau tren penyaluran bantuan MBG</p>
            </div>

            {{-- ══ STAT CARDS ══ --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Penerima</span>
                    <span class="text-2xl font-black" style="color:#4E6F5C">{{ $totalPenerima }}</span>
                </div>
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Distribusi</span>
                    <span class="text-2xl font-black" style="color:#4E6F5C">{{ $totalDistribusi }}</span>
                </div>
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Diterima</span>
                    <span class="text-2xl font-black" style="color:#06B13D">{{ $diterima }}</span>
                </div>
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Pending</span>
                    <span class="text-2xl font-black text-yellow-500">{{ $pending }}</span>
                </div>
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Gagal</span>
                    <span class="text-2xl font-black text-red-500">{{ $gagal }}</span>
                </div>
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Avg Rating</span>
                    <div class="flex items-center gap-1">
                        <span class="text-2xl font-black" style="color:#06B13D">{{ number_format($avgRating, 1) }}</span>
                        <i class="bi bi-star-fill text-yellow-400 text-xs"></i>
                    </div>
                </div>
            </div>

            {{-- ══ CHART ══ --}}
            <div class="bg-white rounded-2xl shadow p-5 mb-4">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-bold" style="color:#4E6F5C">Tren Distribusi</h2>
                        <p class="text-xs text-gray-400">30 hari terakhir</p>
                    </div>
                    <div class="flex items-center gap-4 text-xs">
                        <span class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full inline-block" style="background:#06B13D"></span>
                            Diterima
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full inline-block bg-red-400"></span>
                            Gagal
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full inline-block" style="background:#D7F487"></span>
                            Total
                        </span>
                    </div>
                </div>
                <div style="height:260px;position:relative">
                    <canvas id="distribusiChart" style="display:block;width:100%;height:260px"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">

                {{-- ══ PENERIMA PER KATEGORI ══ --}}
                <div class="bg-white rounded-2xl shadow p-5">
                    <h2 class="text-sm font-bold mb-4" style="color:#4E6F5C">Penerima per Kategori</h2>
                    @php
                        $katLabels = [
                            'ibu_hamil'    => ['label' => 'Ibu Hamil',    'style' => 'background:#FEF3C7;color:#92400E'],
                            'ibu_menyusui' => ['label' => 'Ibu Menyusui', 'style' => 'background:#DBEAFE;color:#1E40AF'],
                            'balita'       => ['label' => 'Balita',        'style' => 'background:#D7F487;color:#3A5C0D'],
                            'lainnya'      => ['label' => 'Lainnya',       'style' => 'background:#F3F4F6;color:#4B5563'],
                        ];
                        $totalPen = max($totalPenerima, 1);
                    @endphp
                    <div class="space-y-3">
                        @foreach($katLabels as $key => $info)
                        @php $count = $penerimaByKat[$key] ?? 0; @endphp
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="font-medium text-gray-600">{{ $info['label'] }}</span>
                                <span class="font-bold" style="color:#4E6F5C">{{ $count }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all"
                                     style="{{ $info['style'] }};width:{{ round($count/$totalPen*100) }}%;background:{{ explode(';', $info['style'])[0] === 'background:#D7F487' ? '#06B13D' : explode(':', explode(';', $info['style'])[0])[1] }}"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('penerima.index') }}"
                       class="mt-4 flex items-center justify-center gap-1.5 text-xs font-semibold py-2 rounded-xl transition"
                       style="background:#D7F487;color:#4E6F5C">
                        Lihat Data Penerima <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                {{-- ══ TOP KADER ══ --}}
                <div class="bg-white rounded-2xl shadow p-5">
                    <h2 class="text-sm font-bold mb-4" style="color:#4E6F5C">Kader Teraktif</h2>
                    <div class="space-y-3">
                        @forelse($topKader as $i => $k)
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-black w-5 text-center"
                                  style="color:{{ $i === 0 ? '#06B13D' : '#9CA3AF' }}">
                                {{ $i + 1 }}
                            </span>
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0"
                                 style="background:#4E6F5C;color:#D7F487">
                                {{ strtoupper(substr($k->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-700 truncate">{{ $k->name }}</p>
                                <p class="text-xs text-gray-400">{{ $k->distribusis_count }} distribusi</p>
                            </div>
                            @if($i === 0)
                            <i class="bi bi-trophy-fill text-yellow-400 text-sm flex-shrink-0"></i>
                            @endif
                        </div>
                        @empty
                        <p class="text-sm text-gray-400 text-center py-4">Belum ada data kader</p>
                        @endforelse
                    </div>
                </div>

                {{-- ══ RECENT FEEDBACK ══ --}}
                <div class="bg-white rounded-2xl shadow p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-bold" style="color:#4E6F5C">Ulasan Terbaru</h2>
                        <a href="{{ route('feedback.index') }}" class="text-xs font-semibold" style="color:#06B13D">Semua →</a>
                    </div>
                    <div class="space-y-3">
                        @forelse($recentFeedback as $f)
                        <a href="{{ route('feedback.show', $f->id) }}"
                           class="block p-3 rounded-xl hover:opacity-90 transition" style="background:#F0FDF4">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-semibold text-gray-700">{{ $f->penerima->nama ?? '-' }}</span>
                                <div class="flex items-center gap-0.5">
                                    @for($s = 1; $s <= 5; $s++)
                                    <i class="bi bi-star-fill text-[10px] {{ $s <= $f->rating ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 truncate">{{ $f->isi_ulasan ?? 'Tidak ada komentar' }}</p>
                        </a>
                        @empty
                        <p class="text-sm text-gray-400 text-center py-4">Belum ada ulasan</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Quick links --}}
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('distribusi.index') }}"
                   class="bg-white rounded-2xl shadow p-4 flex items-center gap-3 hover:shadow-md transition">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#D7F487">
                        <i class="bi bi-truck" style="color:#4E6F5C"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold" style="color:#4E6F5C">Laporan Distribusi</p>
                        <p class="text-xs text-gray-400">Lihat data lengkap</p>
                    </div>
                    <i class="bi bi-arrow-right ml-auto text-gray-300"></i>
                </a>
                <a href="{{ route('laporan.index') }}"
                   class="bg-white rounded-2xl shadow p-4 flex items-center gap-3 hover:shadow-md transition">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#D7F487">
                        <i class="bi bi-download" style="color:#4E6F5C"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold" style="color:#4E6F5C">Unduh Laporan</p>
                        <p class="text-xs text-gray-400">PDF, Excel, atau CSV</p>
                    </div>
                    <i class="bi bi-arrow-right ml-auto text-gray-300"></i>
                </a>
            </div>

        </div>
    </main>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('distribusiChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [
                {
                    label: 'Total',
                    data: @json($totals),
                    borderColor: '#D7F487',
                    backgroundColor: 'rgba(215,244,135,0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 2,
                },
                {
                    label: 'Diterima',
                    data: @json($diterimaArr),
                    borderColor: '#06B13D',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 2,
                },
                {
                    label: 'Gagal',
                    data: @json($gagalArr),
                    borderColor: '#F87171',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 2,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0B1C12',
                    titleColor: '#D7F487',
                    bodyColor: '#FAFCFB',
                    padding: 10,
                    cornerRadius: 8,
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        color: '#9CA3AF',
                        font: { size: 10 },
                        maxTicksLimit: 10,
                    },
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#F3F4F6' },
                    ticks: {
                        color: '#9CA3AF',
                        font: { size: 10 },
                        precision: 0,
                    },
                },
            },
        },
    });
</script>
@endpush
@endsection