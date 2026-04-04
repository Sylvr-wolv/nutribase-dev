@extends('layouts.app')

@section('content')
<div class="flex min-h-screen" style="background:#FAFCFB">

    @include('layouts.sidebar')

    <main
        class="flex-1 flex flex-col min-w-0 lg:pl-72"
        x-data="{
            editModal: false,
            editData: {},
            searchQuery: '{{ request('search') }}',

            submitSearch() {
                const params = new URLSearchParams();
                if (this.searchQuery.trim()) params.set('search', this.searchQuery.trim());
                window.location.href = '{{ route('tanggapan.index') }}' + (params.toString() ? '?' + params.toString() : '');
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
            <div class="mb-6">
                <h1 class="text-xl sm:text-2xl font-bold" style="color:#4E6F5C">Tanggapan</h1>
                <p class="text-gray-500 text-sm">Riwayat tanggapan terhadap ulasan penerima</p>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 gap-3 mb-5">
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Total Tanggapan</span>
                    <span class="text-2xl font-black" style="color:#4E6F5C">{{ $stats['total'] }}</span>
                </div>
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Dari Saya</span>
                    <span class="text-2xl font-black" style="color:#06B13D">{{ $stats['milik_saya'] }}</span>
                </div>
            </div>

            {{-- Search --}}
            <div class="bg-white rounded-2xl shadow px-4 sm:px-6 py-4 mb-4">
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                            </svg>
                        </div>
                        <input type="text" x-model="searchQuery" @keydown.enter="submitSearch()"
                            placeholder="Cari isi tanggapan atau nama kader..."
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
                        style="background:#06B13D">Cari</button>
                </div>
            </div>

            {{-- Desktop Table --}}
            <div class="hidden md:block bg-white rounded-3xl shadow p-4 sm:p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-gray-500 uppercase text-xs border-b">
                            <tr>
                                <th class="py-4 pr-4">No</th>
                                <th class="pr-4">Kader</th>
                                <th class="pr-4">Ulasan (Penerima)</th>
                                <th class="pr-4">Isi Tanggapan</th>
                                <th class="pr-4">Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($tanggapans as $i => $t)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-4 pr-4 text-gray-500">{{ $tanggapans->firstItem() + $i }}</td>
                                <td class="pr-4">
                                    <span class="font-medium text-gray-700">{{ $t->user->name ?? '-' }}</span>
                                    @if($t->user_id === auth()->id())
                                    <span class="ml-1.5 text-[10px] px-1.5 py-0.5 rounded font-bold" style="background:#D7F487;color:#4E6F5C">Saya</span>
                                    @endif
                                </td>
                                <td class="pr-4 max-w-[200px]">
                                    <div class="flex items-center gap-1 mb-1">
                                        @for($s = 1; $s <= 5; $s++)
                                        <i class="bi bi-star-fill text-xs {{ $s <= ($t->feedback->rating ?? 0) ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-gray-500 block truncate">
                                        {{ $t->feedback->penerima->nama ?? '-' }} — {{ $t->feedback->isi_ulasan ?? 'Tidak ada ulasan' }}
                                    </span>
                                </td>
                                <td class="pr-4 text-gray-600 text-sm max-w-[240px]">
                                    <span class="block truncate">{{ $t->isi_tanggapan }}</span>
                                </td>
                                <td class="pr-4 text-xs text-gray-400 whitespace-nowrap">
                                    {{ $t->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="space-x-3 text-xs font-semibold tracking-wide whitespace-nowrap">
                                    @can('update', $t)
                                    <button
                                        @click="
                                            editData = {
                                                id: {{ $t->id }},
                                                isi_tanggapan: @js($t->isi_tanggapan),
                                            };
                                            editModal = true
                                        "
                                        class="transition hover:opacity-70"
                                        style="color:#06B13D">EDIT</button>
                                    @endcan

                                    @can('delete', $t)
                                    <form id="del-tg-{{ $t->id }}" action="{{ route('tanggapan.destroy', $t->id) }}" method="POST" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button type="button"
                                        @click="Swal.fire({ title: 'Hapus tanggapan ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#06B13D', cancelButtonColor: '#d33', confirmButtonText: 'Ya, hapus!' }).then(r => { if (r.isConfirmed) document.getElementById('del-tg-{{ $t->id }}').submit() })"
                                        class="text-red-500 hover:text-red-700 transition">HAPUS</button>
                                    @endcan
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-gray-400">
                                    <i class="bi bi-reply-all text-4xl block mb-2"></i>
                                    Belum ada tanggapan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Mobile Cards --}}
            <div class="md:hidden space-y-3">
                @forelse($tanggapans as $t)
                <div class="bg-white rounded-2xl shadow p-4">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div>
                            <span class="font-semibold text-gray-800 text-sm">{{ $t->user->name ?? '-' }}</span>
                            @if($t->user_id === auth()->id())
                            <span class="ml-1.5 text-[10px] px-1.5 py-0.5 rounded font-bold" style="background:#D7F487;color:#4E6F5C">Saya</span>
                            @endif
                        </div>
                        <span class="text-xs text-gray-400 flex-shrink-0">{{ $t->created_at->format('d M Y') }}</span>
                    </div>

                    {{-- Feedback context --}}
                    <div class="rounded-lg p-2.5 mb-3" style="background:#F0FDF4">
                        <div class="flex items-center gap-1 mb-1">
                            @for($s = 1; $s <= 5; $s++)
                            <i class="bi bi-star-fill text-xs {{ $s <= ($t->feedback->rating ?? 0) ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                            @endfor
                            <span class="text-xs text-gray-500 ml-1">{{ $t->feedback->penerima->nama ?? '-' }}</span>
                        </div>
                        <p class="text-xs text-gray-500 truncate">{{ $t->feedback->isi_ulasan ?? 'Tidak ada ulasan' }}</p>
                    </div>

                    <p class="text-sm text-gray-700 mb-3">{{ $t->isi_tanggapan }}</p>

                    @if(auth()->id() === $t->user_id)
                    <div class="flex gap-2 pt-3 border-t border-gray-100">
                        @can('update', $t)
                        <button
                            @click="
                                editData = { id: {{ $t->id }}, isi_tanggapan: @js($t->isi_tanggapan) };
                                editModal = true
                            "
                            class="flex-1 text-xs font-semibold py-2 rounded-lg transition text-white"
                            style="background:#06B13D">EDIT</button>
                        @endcan
                        @can('delete', $t)
                        <button type="button"
                            @click="Swal.fire({ title: 'Hapus tanggapan ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#06B13D', cancelButtonColor: '#d33', confirmButtonText: 'Ya, hapus!' }).then(r => { if (r.isConfirmed) document.getElementById('del-tg-{{ $t->id }}').submit() })"
                            class="flex-1 text-xs font-semibold text-red-500 bg-red-50 hover:bg-red-100 py-2 rounded-lg transition">HAPUS</button>
                        @endcan
                    </div>
                    @endif
                </div>
                @empty
                <div class="bg-white rounded-2xl shadow p-10 text-center text-gray-400">
                    <i class="bi bi-reply-all text-4xl block mb-2"></i>
                    Belum ada tanggapan
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white rounded-2xl shadow px-5 py-4">
                <p class="text-sm text-gray-500 order-2 sm:order-1">
                    Menampilkan <span class="font-semibold text-gray-700">{{ $tanggapans->count() }}</span>
                    dari <span class="font-semibold text-gray-700">{{ $tanggapans->total() }}</span> data
                </p>
                <div class="flex items-center gap-1 order-1 sm:order-2">
                    <span class="w-9 h-9 flex items-center justify-center rounded-xl {{ $tanggapans->onFirstPage() ? 'text-gray-300 cursor-not-allowed' : 'text-gray-500 cursor-pointer' }}">
                        @if(!$tanggapans->onFirstPage())
                            <a href="{{ $tanggapans->previousPageUrl() }}" class="w-full h-full flex items-center justify-center hover:bg-gray-100 rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            </a>
                        @else
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        @endif
                    </span>
                    @foreach($tanggapans->getUrlRange(max(1, $tanggapans->currentPage() - 2), min($tanggapans->lastPage(), $tanggapans->currentPage() + 2)) as $page => $url)
                        @if($page == $tanggapans->currentPage())
                            <span class="w-9 h-9 flex items-center justify-center rounded-xl text-white text-sm font-bold" style="background:#06B13D">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center rounded-xl text-gray-600 hover:bg-gray-100 text-sm transition">{{ $page }}</a>
                        @endif
                    @endforeach
                    <span class="w-9 h-9 flex items-center justify-center rounded-xl {{ $tanggapans->hasMorePages() ? 'text-gray-500 cursor-pointer' : 'text-gray-300 cursor-not-allowed' }}">
                        @if($tanggapans->hasMorePages())
                            <a href="{{ $tanggapans->nextPageUrl() }}" class="w-full h-full flex items-center justify-center hover:bg-gray-100 rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @else
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        @endif
                    </span>
                </div>
            </div>

        </div>

        @include('tanggapan.form')

    </main>
</div>
@endsection