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

            searchQuery: '{{ request('search') }}',
            filterRole: '{{ request('role') }}',

            submitSearch() {
                const params = new URLSearchParams();
                if (this.searchQuery.trim()) params.set('search', this.searchQuery.trim());
                if (this.filterRole)         params.set('role', this.filterRole);
                window.location.href = '{{ route('users.index') }}' + (params.toString() ? '?' + params.toString() : '');
            },

            roleColor(role) {
                if (role === 'kader')       return 'background:#D7F487;color:#4E6F5C';
                if (role === 'koordinator') return 'background:#DBEAFE;color:#1E40AF';
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
                    <h1 class="text-xl sm:text-2xl font-bold" style="color:#4E6F5C">Manajemen Pengguna</h1>
                    <p class="text-gray-500 text-sm">Kelola akun penerima dan pengguna sistem</p>
                </div>

                @can('create', App\Models\User::class)
                <button
                    @click="openModal = true"
                    class="self-start sm:self-auto font-medium px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl flex items-center gap-2 shadow-sm transition text-sm sm:text-base whitespace-nowrap text-white"
                    style="background:#06B13D">
                    <span class="bg-white/20 w-6 h-6 flex items-center justify-center rounded-full text-sm flex-shrink-0">+</span>
                    Tambah Pengguna
                </button>
                @endcan
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Total</span>
                    <span class="text-2xl font-black" style="color:#4E6F5C">{{ $stats['total'] }}</span>
                </div>
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Penerima</span>
                    <span class="text-2xl font-black" style="color:#854D0E">{{ $stats['penerima'] }}</span>
                </div>
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Kader</span>
                    <span class="text-2xl font-black" style="color:#06B13D">{{ $stats['kader'] }}</span>
                </div>
                <div class="bg-white rounded-2xl shadow px-4 py-3 flex flex-col gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Koordinator</span>
                    <span class="text-2xl font-black" style="color:#1E40AF">{{ $stats['koordinator'] }}</span>
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
                            placeholder="Cari nama atau username..."
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

                <div class="flex flex-col sm:flex-row gap-3 flex-wrap items-end">
                    <div class="flex flex-col gap-1 min-w-[160px]">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Role</label>
                        <div class="relative">
                            <select x-model="filterRole" @change="submitSearch()"
                                class="w-full appearance-none bg-gray-100 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300 transition cursor-pointer pr-8">
                                <option value="">Semua Role</option>
                                <option value="penerima"   {{ request('role') === 'penerima'   ? 'selected' : '' }}>Penerima</option>
                                <option value="kader"      {{ request('role') === 'kader'      ? 'selected' : '' }}>Kader</option>
                                <option value="koordinator"{{ request('role') === 'koordinator'? 'selected' : '' }}>Koordinator</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-2.5 flex items-center">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap self-end">
                        @if(request('search') || request('role'))
                        <a href="{{ route('users.index') }}"
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

            @if(request('search') || request('role'))
            <p class="text-sm text-gray-500 mb-3 px-1">
                Ditemukan <span class="font-semibold text-gray-700">{{ $users->total() }}</span> pengguna
            </p>
            @endif

            {{-- Desktop Table --}}
            <div class="hidden md:block bg-white rounded-3xl shadow p-4 sm:p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-gray-500 uppercase text-xs border-b">
                            <tr>
                                <th class="py-4 pr-4">No</th>
                                <th class="pr-4">Nama</th>
                                <th class="pr-4">Username</th>
                                <th class="pr-4">Role</th>
                                <th class="pr-4">Terdaftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($users as $i => $u)
                            @php
                                $roleStyle = match($u->role) {
                                    'kader'       => 'background:#D7F487;color:#4E6F5C',
                                    'koordinator' => 'background:#DBEAFE;color:#1E40AF',
                                    default       => 'background:#FEF9C3;color:#854D0E',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-4 pr-4 text-gray-500">{{ $users->firstItem() + $i }}</td>
                                <td class="pr-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0"
                                             style="background:#4E6F5C;color:#D7F487">
                                            {{ strtoupper(substr($u->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-800">{{ $u->name }}</span>
                                            @if($u->id === auth()->id())
                                            <span class="ml-1.5 text-[10px] px-1.5 py-0.5 rounded font-bold" style="background:#D7F487;color:#4E6F5C">Saya</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="pr-4 text-gray-500 font-mono text-xs">{{ $u->username }}</td>
                                <td class="pr-4">
                                    <span class="text-xs px-3 py-1 rounded-full font-semibold capitalize"
                                          style="{{ $roleStyle }}">
                                        {{ $u->role }}
                                    </span>
                                </td>
                                <td class="pr-4 text-xs text-gray-400">{{ $u->created_at->format('d M Y') }}</td>
                                <td class="space-x-3 text-xs font-semibold tracking-wide whitespace-nowrap">
                                    @can('update', $u)
                                    <button
                                        @click="
                                            editData = {
                                                id: {{ $u->id }},
                                                name: @js($u->name),
                                                username: @js($u->username),
                                                role: '{{ $u->role }}',
                                            };
                                            editModal = true
                                        "
                                        class="transition hover:opacity-70"
                                        style="color:#06B13D">EDIT</button>
                                    @endcan

                                    @can('delete', $u)
                                    <form id="del-user-{{ $u->id }}" action="{{ route('users.destroy', $u->id) }}" method="POST" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button type="button"
                                        @click="
                                            Swal.fire({
                                                title: 'Hapus pengguna ini?',
                                                text: '{{ $u->name }}',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#06B13D',
                                                cancelButtonColor: '#d33',
                                                confirmButtonText: 'Ya, hapus!'
                                            }).then(r => { if (r.isConfirmed) document.getElementById('del-user-{{ $u->id }}').submit() })
                                        "
                                        class="text-red-500 hover:text-red-700 transition">HAPUS</button>
                                    @endcan
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-gray-400">
                                    <i class="bi bi-people text-4xl block mb-2"></i>
                                    Belum ada pengguna
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Mobile Cards --}}
            <div class="md:hidden space-y-3">
                @forelse($users as $u)
                @php
                    $roleStyle = match($u->role) {
                        'kader'       => 'background:#D7F487;color:#4E6F5C',
                        'koordinator' => 'background:#DBEAFE;color:#1E40AF',
                        default       => 'background:#FEF9C3;color:#854D0E',
                    };
                @endphp
                <div class="bg-white rounded-2xl shadow p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold flex-shrink-0"
                             style="background:#4E6F5C;color:#D7F487">
                            {{ strtoupper(substr($u->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="font-semibold text-gray-800">{{ $u->name }}</p>
                                @if($u->id === auth()->id())
                                <span class="text-[10px] px-1.5 py-0.5 rounded font-bold" style="background:#D7F487;color:#4E6F5C">Saya</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400 font-mono">{{ $u->username }}</p>
                        </div>
                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold capitalize flex-shrink-0"
                              style="{{ $roleStyle }}">
                            {{ $u->role }}
                        </span>
                    </div>

                    <p class="text-xs text-gray-400 mb-3">Terdaftar {{ $u->created_at->format('d M Y') }}</p>

                    <div class="flex gap-2 pt-3 border-t border-gray-100">
                        @can('update', $u)
                        <button
                            @click="
                                editData = {
                                    id: {{ $u->id }},
                                    name: @js($u->name),
                                    username: @js($u->username),
                                    role: '{{ $u->role }}',
                                };
                                editModal = true
                            "
                            class="flex-1 text-xs font-semibold py-2 rounded-lg transition text-white"
                            style="background:#06B13D">
                            EDIT
                        </button>
                        @endcan

                        @can('delete', $u)
                        <button type="button"
                            @click="
                                Swal.fire({
                                    title: 'Hapus pengguna ini?',
                                    text: '{{ $u->name }}',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#06B13D',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ya, hapus!'
                                }).then(r => { if (r.isConfirmed) document.getElementById('del-user-{{ $u->id }}').submit() })
                            "
                            class="flex-1 text-xs font-semibold text-red-500 bg-red-50 hover:bg-red-100 py-2 rounded-lg transition">
                            HAPUS
                        </button>
                        @endcan
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-2xl shadow p-10 text-center text-gray-400">
                    <i class="bi bi-people text-4xl block mb-2"></i>
                    Belum ada pengguna
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white rounded-2xl shadow px-5 py-4">
                <p class="text-sm text-gray-500 order-2 sm:order-1">
                    Menampilkan <span class="font-semibold text-gray-700">{{ $users->count() }}</span>
                    dari <span class="font-semibold text-gray-700">{{ $users->total() }}</span> pengguna
                </p>
                <div class="flex items-center gap-1 order-1 sm:order-2">
                    <span class="w-9 h-9 flex items-center justify-center rounded-xl {{ $users->onFirstPage() ? 'text-gray-300 cursor-not-allowed' : 'text-gray-500' }}">
                        @if(!$users->onFirstPage())
                            <a href="{{ $users->previousPageUrl() }}" class="w-full h-full flex items-center justify-center hover:bg-gray-100 rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            </a>
                        @else
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        @endif
                    </span>
                    @foreach($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
                        @if($page == $users->currentPage())
                            <span class="w-9 h-9 flex items-center justify-center rounded-xl text-white text-sm font-bold" style="background:#06B13D">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center rounded-xl text-gray-600 hover:bg-gray-100 text-sm transition">{{ $page }}</a>
                        @endif
                    @endforeach
                    <span class="w-9 h-9 flex items-center justify-center rounded-xl {{ $users->hasMorePages() ? 'text-gray-500' : 'text-gray-300 cursor-not-allowed' }}">
                        @if($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}" class="w-full h-full flex items-center justify-center hover:bg-gray-100 rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @else
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        @endif
                    </span>
                </div>
            </div>

        </div>

        @include('users.form')

    </main>
</div>
@endsection