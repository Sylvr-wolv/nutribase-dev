@php
    function navActive(string|array $routes): bool {
        return request()->routeIs($routes);
    }
@endphp

<style>

    .nb-sidebar {
        font-family: 'DM Sans', sans-serif;
        background: #0B1120;
        border-right: 1px solid rgba(255,255,255,0.06);
    }

    .nb-logo-text {
        font-family: 'Space Grotesk', sans-serif;
    }

    .nb-nav-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        border-radius: 10px;
        color: #8694A8;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.18s ease;
        cursor: pointer;
        text-decoration: none;
        position: relative;
    }

    .nb-nav-item:hover {
        background: rgba(255,255,255,0.05);
        color: #E2E8F0;
    }

    .nb-nav-item:hover .nb-nav-icon {
        color: #4ADE80;
    }

    .nb-nav-item.active {
        background: linear-gradient(135deg, rgba(74,222,128,0.12), rgba(74,222,128,0.05));
        color: #fff;
        border: 1px solid rgba(74,222,128,0.2);
    }

    .nb-nav-item.active .nb-nav-icon {
        color: #4ADE80;
    }

    .nb-nav-item.active::before {
        content: '';
        position: absolute;
        left: -1px;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 60%;
        background: #4ADE80;
        border-radius: 0 4px 4px 0;
    }

    .nb-nav-sub {
        padding-left: 22px;
        font-size: 13px;
        color: #94A3B8;
    }

    .nb-nav-sub .nb-nav-icon {
        font-size: 14px;
        opacity: 0.85;
    }

    .nb-nav-icon {
        width: 18px;
        font-size: 16px;
        text-align: center;
        flex-shrink: 0;
        color: #4A5568;
        transition: color 0.18s ease;
    }

    .nb-section-label {
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #2D3A4A;
        padding: 0 14px;
        margin-top: 20px;
        margin-bottom: 6px;
    }

    .nb-section-label:first-of-type {
        margin-top: 0;
    }

    .nb-user-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 12px;
        padding: 12px;
    }

    .nb-avatar {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #166534, #4ADE80);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 700;
        color: white;
        flex-shrink: 0;
        font-family: 'Space Grotesk', sans-serif;
    }

    .nb-logo-pill {
        background: linear-gradient(135deg, #4ADE80, #22C55E);
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .nb-scrollbar::-webkit-scrollbar { width: 4px; }
    .nb-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .nb-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); border-radius: 4px; }

    .nb-divider {
        height: 1px;
        background: rgba(255,255,255,0.05);
        margin: 8px 0;
    }
</style>

<aside
    x-cloak
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="nb-sidebar fixed inset-y-0 left-0 z-40
           w-64 flex flex-col flex-shrink-0
           transition-transform duration-300 ease-in-out">

    <div class="p-4 pb-0">
        <div class="flex items-center gap-3 px-1 py-3">
            <div class="nb-logo-pill">
                <i class="bi bi-heart-pulse-fill text-white text-lg"></i>
            </div>
            <div>
                <p class="nb-logo-text text-white font-bold text-base leading-tight tracking-tight">NutriBase</p>
                <p class="text-[10px] text-slate-500 tracking-wider uppercase">MBG Platform</p>
            </div>
            <button
                @click="sidebarOpen = false"
                class="ml-auto lg:hidden text-slate-600 hover:text-slate-300 transition">
                <i class="bi bi-x-lg text-base"></i>
            </button>
        </div>

        <div class="nb-divider mt-3"></div>
    </div>

    <nav class="flex-1 px-3 mt-2 overflow-y-auto nb-scrollbar pb-4 space-y-0.5">

        @if(auth()->user()->role === 'kader')

            <p class="nb-section-label">Dashboard</p>
            <a href="{{ route('dashboard') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item {{ navActive('dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-door-fill nb-nav-icon"></i>
                Beranda
            </a>
            <a href="{{ route('kader.penerima.index') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item nb-nav-sub {{ navActive('kader.penerima.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill nb-nav-icon"></i>
                Penerima
            </a>
            <a href="{{ route('kader.menu.index') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item nb-nav-sub {{ navActive('kader.menu.*') ? 'active' : '' }}">
                <i class="bi bi-egg-fried nb-nav-icon"></i>
                Menu
            </a>
            <a href="{{ route('kader.distribusi.index') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item nb-nav-sub {{ navActive('kader.distribusi.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam-fill nb-nav-icon"></i>
                Distribusi
            </a>

            <p class="nb-section-label">Manajemen Penerima</p>
            <a href="{{ route('kader.users.index') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item {{ navActive('kader.users.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge-fill nb-nav-icon"></i>
                Pengguna
            </a>
            <a href="{{ route('kader.penerima.index') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item {{ navActive('kader.penerima.*') ? 'active' : '' }}">
                <i class="bi bi-person-lines-fill nb-nav-icon"></i>
                Data Penerima
            </a>

            <p class="nb-section-label">Menu &amp; Stok</p>
            <a href="{{ route('kader.menu.index') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item {{ navActive('kader.menu.*') ? 'active' : '' }}">
                <i class="bi bi-basket2-fill nb-nav-icon"></i>
                Menu &amp; Stok
            </a>

            <p class="nb-section-label">Jadwal</p>
            <a href="{{ route('kader.jadwal.index') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item {{ navActive('kader.jadwal.*') ? 'active' : '' }}">
                <i class="bi bi-calendar2-week-fill nb-nav-icon"></i>
                Jadwal
            </a>

            <p class="nb-section-label">Distribusi</p>
            <a href="{{ route('kader.distribusi.index') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item {{ navActive('kader.distribusi.*') ? 'active' : '' }}">
                <i class="bi bi-truck nb-nav-icon"></i>
                Data Distribusi
            </a>
            <a href="{{ route('kader.jadwal.index') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item nb-nav-sub {{ navActive('kader.jadwal.*') ? 'active' : '' }}">
                <i class="bi bi-link-45deg nb-nav-icon"></i>
                Relasi (jadwal)
            </a>

            <p class="nb-section-label">Feedback</p>
            <a href="{{ route('kader.feedback.index') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item {{ navActive('kader.feedback.*') ? 'active' : '' }}">
                <i class="bi bi-chat-square-text-fill nb-nav-icon"></i>
                Ulasan
            </a>
            <a href="{{ route('kader.tanggapan.index') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item {{ navActive('kader.tanggapan.*') ? 'active' : '' }}">
                <i class="bi bi-reply-all-fill nb-nav-icon"></i>
                Tanggapan
            </a>

        @elseif(auth()->user()->role === 'koordinator')

            <p class="nb-section-label">Dashboard</p>
            <a href="{{ route('dashboard') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item {{ navActive('dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-door-fill nb-nav-icon"></i>
                Beranda
            </a>
            <a href="{{ route('koordinator.laporan.distribusi') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item nb-nav-sub {{ navActive('koordinator.laporan.distribusi') ? 'active' : '' }}">
                <i class="bi bi-box-seam-fill nb-nav-icon"></i>
                Distribusi
            </a>
            <a href="{{ route('koordinator.laporan.penerima') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item nb-nav-sub {{ navActive('koordinator.laporan.penerima') ? 'active' : '' }}">
                <i class="bi bi-people-fill nb-nav-icon"></i>
                Penerima
            </a>
            <a href="{{ route('koordinator.laporan.menu') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item nb-nav-sub {{ navActive('koordinator.laporan.menu') ? 'active' : '' }}">
                <i class="bi bi-egg-fried nb-nav-icon"></i>
                Menu
            </a>

            <p class="nb-section-label">Laporan</p>
            <a href="{{ route('koordinator.laporan.distribusi') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item {{ navActive('koordinator.laporan.distribusi') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow nb-nav-icon"></i>
                Distribusi
            </a>
            <a href="{{ route('koordinator.laporan.penerima') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item {{ navActive('koordinator.laporan.penerima') ? 'active' : '' }}">
                <i class="bi bi-person-vcard nb-nav-icon"></i>
                Penerima
            </a>
            <a href="{{ route('koordinator.laporan.menu') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item {{ navActive('koordinator.laporan.menu') ? 'active' : '' }}">
                <i class="bi bi-cup-hot-fill nb-nav-icon"></i>
                Menu
            </a>
            <a href="{{ route('koordinator.laporan.jadwal') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item {{ navActive('koordinator.laporan.jadwal') ? 'active' : '' }}">
                <i class="bi bi-calendar3 nb-nav-icon"></i>
                Jadwal
            </a>
            <a href="{{ route('koordinator.laporan.users') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item {{ navActive('koordinator.laporan.users') ? 'active' : '' }}">
                <i class="bi bi-people nb-nav-icon"></i>
                Pengguna
            </a>

            <p class="nb-section-label">Tanggapan</p>
            <a href="{{ route('koordinator.tanggapan.index') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item {{ navActive('koordinator.tanggapan.*') ? 'active' : '' }}">
                <i class="bi bi-reply-all-fill nb-nav-icon"></i>
                Tanggapan
            </a>
            <a href="{{ route('koordinator.feedback.index') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item {{ navActive('koordinator.feedback.*') ? 'active' : '' }}">
                <i class="bi bi-chat-left-text-fill nb-nav-icon"></i>
                Feedback
            </a>

        @elseif(auth()->user()->role === 'penerima')

            <p class="nb-section-label">Dashboard</p>
            <a href="{{ route('dashboard') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item {{ navActive('dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-door-fill nb-nav-icon"></i>
                Beranda
            </a>
            <a href="{{ route('penerima.riwayat') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item nb-nav-sub {{ navActive('penerima.riwayat') ? 'active' : '' }}">
                <i class="bi bi-box-seam-fill nb-nav-icon"></i>
                Distribusi
            </a>

            <p class="nb-section-label">Riwayat Bantuan</p>
            <a href="{{ route('penerima.riwayat') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item {{ navActive('penerima.riwayat') ? 'active' : '' }}">
                <i class="bi bi-clock-history nb-nav-icon"></i>
                Distribusi
            </a>
            <a href="{{ route('penerima.menu') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item {{ navActive('penerima.menu') ? 'active' : '' }}">
                <i class="bi bi-egg-fried nb-nav-icon"></i>
                Menu
            </a>

            <p class="nb-section-label">Tanggapan</p>
            <a href="{{ route('penerima.feedback.index') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item {{ navActive('penerima.feedback.*') ? 'active' : '' }}">
                <i class="bi bi-star-fill nb-nav-icon"></i>
                Feedback
            </a>
            <a href="{{ route('penerima.tanggapan.index') }}"
               @click="sidebarOpen = false"
               class="nb-nav-item {{ navActive('penerima.tanggapan.*') ? 'active' : '' }}">
                <i class="bi bi-chat-dots-fill nb-nav-icon"></i>
                Tanggapan
            </a>

        @endif

    </nav>

    <div class="p-3 border-t border-white/5">
        <div class="nb-user-card">
            <div class="flex items-center gap-3">
                <div class="nb-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">
                        {{ auth()->user()->name ?? 'Guest' }}
                    </p>
                    <p class="text-xs text-slate-500 truncate capitalize">
                        {{ auth()->user()->role ?? '' }}
                    </p>
                </div>
                <a href="{{ route('profile.show') }}"
                   title="Profil"
                   class="text-slate-600 hover:text-green-400 transition flex-shrink-0">
                    <i class="bi bi-person-circle text-lg"></i>
                </a>
            </div>
        </div>
    </div>

</aside>
