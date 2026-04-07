@php
    function navActive(string|array $routes): bool {
        return request()->routeIs($routes);
    }
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Space+Grotesk:wght@600;700&display=swap');

    .nb-sidebar {
        font-family: 'DM Sans', sans-serif;
        background: #0B1C12;
        border-right: 1px solid rgba(255,255,255,0.05);
    }
    .nb-logo-text { font-family: 'Space Grotesk', sans-serif; }

    .nb-nav-item {
        display: flex; align-items: center; gap: 11px;
        padding: 9px 13px; border-radius: 9px;
        color: #7A9483; font-size: 13.5px; font-weight: 500;
        transition: all 0.15s; text-decoration: none;
        position: relative; border: 1px solid transparent;
    }
    .nb-nav-item:hover { background: rgba(215,244,135,0.06); color: #D7F487; }
    .nb-nav-item:hover .nb-nav-icon { color: #79C80E; }

    .nb-nav-item.active {
        background: rgba(6,177,61,0.1);
        border-color: rgba(6,177,61,0.2);
        color: #FAFCFB;
    }
    .nb-nav-item.active .nb-nav-icon { color: #06B13D; }
    .nb-nav-item.active::before {
        content: '';
        position: absolute; left: -1px; top: 20%; height: 60%; width: 3px;
        background: #06B13D; border-radius: 0 3px 3px 0;
    }

    .nb-nav-icon {
        width: 17px; font-size: 15px; text-align: center;
        flex-shrink: 0; color: #3D5245; transition: color 0.15s;
    }

    .nb-section-label {
        font-size: 9.5px; font-weight: 700; letter-spacing: 0.13em;
        text-transform: uppercase; color: #243B2C;
        padding: 0 13px; margin: 18px 0 4px;
    }

    .nb-divider { height: 1px; background: rgba(255,255,255,0.04); margin: 10px 0; }

    .nb-logo-pill {
        width: 36px; height: 36px; border-radius: 10px;
        background: #06B13D;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }

    .nb-scrollbar::-webkit-scrollbar { width: 3px; }
    .nb-scrollbar::-webkit-scrollbar-thumb { background: rgba(215,244,135,0.1); border-radius: 3px; }

    .nb-user-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 11px; padding: 11px;
    }
    .nb-avatar {
        width: 34px; height: 34px; border-radius: 9px;
        background: #4E6F5C;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Space Grotesk', sans-serif;
        font-size: 13px; font-weight: 700; color: #D7F487; flex-shrink: 0;
    }
</style>

<aside
    x-cloak
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="nb-sidebar fixed inset-y-0 left-0 z-40 w-64 flex flex-col flex-shrink-0 transition-transform duration-300 ease-in-out">

    {{-- Logo --}}
    <div class="p-4 pb-0">
        <div class="flex items-center gap-3 px-1 py-2">
            <div class="nb-logo-pill overflow-hidden">
                <img src="{{ asset('favicon.ico') }}" 
                    alt="Logo" 
                    class="w-full h-full object-contain">
            </div>
            <div>
                <p class="nb-logo-text font-bold text-base leading-tight" style="color:#FAFCFB;letter-spacing:-0.02em;">NutriBase</p>
                <p style="font-size:9px;color:#2D4A35;letter-spacing:0.13em;" class="uppercase">MBG Platform</p>
            </div>
            <button @click="sidebarOpen = false" class="ml-auto lg:hidden transition" style="color:#3D5245;">
                <i class="bi bi-x-lg" style="font-size:15px;"></i>
            </button>
        </div>
        <div class="nb-divider mt-2"></div>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-3 mt-1 overflow-y-auto nb-scrollbar pb-4 space-y-0.5">

        @php $role = auth()->user()->role; @endphp

        {{-- ── KADER ── --}}
        @if($role === 'kader')

            <p class="nb-section-label" style="margin-top:10px;">Utama</p>

            <a href="{{ route('dashboard') }}" @click="sidebarOpen=false"
               class="nb-nav-item {{ navActive('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill nb-nav-icon"></i> Dashboard
            </a>

            <p class="nb-section-label">Penerima</p>

            <a href="{{ route('users.index') }}" @click="sidebarOpen=false"
               class="nb-nav-item {{ navActive('users.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge-fill nb-nav-icon"></i> Pengguna
            </a>
            <a href="{{ route('penerima.index') }}" @click="sidebarOpen=false"
               class="nb-nav-item {{ navActive('penerima.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill nb-nav-icon"></i> Data Penerima
            </a>

            <p class="nb-section-label">Menu & Stok</p>

            <a href="{{ route('menu.index') }}" @click="sidebarOpen=false"
               class="nb-nav-item {{ navActive('menu.*') ? 'active' : '' }}">
                <i class="bi bi-basket2-fill nb-nav-icon"></i> Menu & Stok
            </a>

            <p class="nb-section-label">Distribusi</p>

            <a href="{{ route('jadwal.index') }}" @click="sidebarOpen=false"
               class="nb-nav-item {{ navActive('jadwal.*') ? 'active' : '' }}">
                <i class="bi bi-calendar2-week-fill nb-nav-icon"></i> Jadwal
            </a>
            <a href="{{ route('distribusi.index') }}" @click="sidebarOpen=false"
               class="nb-nav-item {{ navActive('distribusi.*') ? 'active' : '' }}">
                <i class="bi bi-truck nb-nav-icon"></i> Data Distribusi
            </a>

            <p class="nb-section-label">Feedback</p>

            <a href="{{ route('feedback.index') }}" @click="sidebarOpen=false"
               class="nb-nav-item {{ navActive('feedback.*') ? 'active' : '' }}">
                <i class="bi bi-chat-square-text-fill nb-nav-icon"></i> Ulasan
            </a>

        {{-- ── KOORDINATOR ── --}}
        @elseif($role === 'koordinator')

            <p class="nb-section-label" style="margin-top:10px;">Utama</p>

            <a href="{{ route('dashboard') }}" @click="sidebarOpen=false"
               class="nb-nav-item {{ navActive('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill nb-nav-icon"></i> Dashboard
            </a>

            <p class="nb-section-label">Laporan</p>
            <a href="{{ route('laporan.index') }}" @click="sidebarOpen=false"
            class="nb-nav-item {{ navActive('laporan.*') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow nb-nav-icon"></i> Laporan
            </a>

            <p class="nb-section-label">Feedback</p>

            <a href="{{ route('feedback.index') }}" @click="sidebarOpen=false"
               class="nb-nav-item {{ navActive('feedback.*') ? 'active' : '' }}">
                <i class="bi bi-chat-left-text-fill nb-nav-icon"></i> Ulasan
            </a>

        {{-- ── PENERIMA ── --}}
        @elseif($role === 'penerima')

            <p class="nb-section-label" style="margin-top:10px;">Utama</p>

            <a href="{{ route('dashboard') }}" @click="sidebarOpen=false"
            class="nb-nav-item {{ navActive('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill nb-nav-icon"></i> Dashboard
            </a>

            <p class="nb-section-label">Riwayat Bantuan</p>

            <a href="{{ route('riwayat') }}" @click="sidebarOpen=false"
            class="nb-nav-item {{ navActive('riwayat') ? 'active' : '' }}">
                <i class="bi bi-clock-history nb-nav-icon"></i> Riwayat Distribusi
            </a>

            <p class="nb-section-label">Feedback</p>

            <a href="{{ route('feedback.index') }}" @click="sidebarOpen=false"
            class="nb-nav-item {{ navActive('feedback.*') ? 'active' : '' }}">
                <i class="bi bi-star-fill nb-nav-icon"></i> Ulasan Saya
            </a>

        @endif

    </nav>

    {{-- User card --}}
    <div class="p-3" style="border-top:1px solid rgba(255,255,255,0.04);">
        <div class="nb-user-card">
            <div class="flex items-center gap-3">
                <div class="nb-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate" style="color:#FAFCFB;">
                        {{ auth()->user()->name ?? 'Guest' }}
                    </p>
                    <p class="text-xs truncate capitalize" style="color:#3D5245;">
                        {{ auth()->user()->role ?? '' }}
                    </p>
                </div>
                <a href="{{ route('profile.show') }}" title="Profil"
                   class="transition flex-shrink-0" style="color:#3D5245;">
                    <i class="bi bi-person-circle" style="font-size:18px;"></i>
                </a>
            </div>
        </div>
    </div>

</aside>