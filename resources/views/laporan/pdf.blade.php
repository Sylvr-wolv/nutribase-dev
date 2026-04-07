<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'DejaVu Sans', Arial, sans-serif;
        font-size: 9px;
        color: #1a1a1a;
        background: #fff;
    }

    /* ── Header ── */
    .header {
        display: table;
        width: 100%;
        border-bottom: 3px solid #06B13D;
        padding-bottom: 10px;
        margin-bottom: 16px;
    }
    .header-logo {
        display: table-cell;
        width: 52px;
        vertical-align: middle;
    }
    .logo-pill {
        width: 44px;
        height: 44px;
        background: #06B13D;
        border-radius: 8px;
        text-align: center;
        line-height: 44px;
        font-size: 22px;
        color: white;
        font-weight: bold;
    }
    .header-text {
        display: table-cell;
        vertical-align: middle;
        padding-left: 10px;
    }
    .header-text h1 {
        font-size: 18px;
        font-weight: bold;
        color: #0B1C12;
        letter-spacing: -0.3px;
    }
    .header-text p {
        font-size: 8px;
        color: #4E6F5C;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-top: 2px;
    }
    .header-meta {
        display: table-cell;
        vertical-align: middle;
        text-align: right;
        width: 200px;
    }
    .header-meta .doc-title {
        font-size: 11px;
        font-weight: bold;
        color: #06B13D;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .header-meta .doc-sub {
        font-size: 8px;
        color: #7A9483;
        margin-top: 3px;
    }

    /* ── Periode badge ── */
    .periode-bar {
        background: #D7F487;
        border-radius: 6px;
        padding: 6px 12px;
        margin-bottom: 16px;
        font-size: 8.5px;
        color: #4E6F5C;
    }
    .periode-bar strong { color: #06B13D; }

    /* ── Stats row ── */
    .stats-row {
        display: table;
        width: 100%;
        margin-bottom: 16px;
        border-spacing: 6px;
    }
    .stat-cell {
        display: table-cell;
        background: #F0FDF4;
        border: 1px solid #D7F487;
        border-radius: 6px;
        padding: 8px 10px;
        text-align: center;
        width: 25%;
    }
    .stat-label {
        font-size: 7px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #7A9483;
        margin-bottom: 3px;
    }
    .stat-value {
        font-size: 16px;
        font-weight: bold;
        color: #06B13D;
    }
    .stat-value.danger { color: #DC2626; }
    .stat-value.dark   { color: #4E6F5C; }

    /* ── Section title ── */
    .section-title {
        font-size: 10px;
        font-weight: bold;
        color: #fff;
        background: #4E6F5C;
        padding: 6px 10px;
        border-radius: 5px 5px 0 0;
        margin-bottom: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ── Table ── */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 8px;
    }
    thead tr th {
        background: #4E6F5C;
        color: #fff;
        padding: 5px 6px;
        text-align: left;
        font-weight: bold;
        font-size: 7.5px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    tbody tr td {
        padding: 4px 6px;
        border-bottom: 1px solid #F0F5F2;
        vertical-align: top;
    }
    tbody tr:nth-child(even) td { background: #F7FCF8; }
    tbody tr:last-child td { border-bottom: none; }

    .badge {
        display: inline-block;
        padding: 2px 6px;
        border-radius: 10px;
        font-size: 7px;
        font-weight: bold;
    }
    .badge-diterima { background: #D7F487; color: #4E6F5C; }
    .badge-gagal    { background: #FEE2E2; color: #991B1B; }
    .badge-pending  { background: #FEF9C3; color: #854D0E; }
    .badge-hamil    { background: #FEF3C7; color: #92400E; }
    .badge-menyusui { background: #DBEAFE; color: #1E40AF; }
    .badge-balita   { background: #D7F487; color: #3A5C0D; }
    .badge-lainnya  { background: #F3F4F6; color: #4B5563; }

    /* ── Footer ── */
    .footer {
        border-top: 1px solid #DFF0E5;
        padding-top: 8px;
        margin-top: 4px;
        display: table;
        width: 100%;
    }
    .footer-left {
        display: table-cell;
        font-size: 7px;
        color: #9CA3AF;
    }
    .footer-right {
        display: table-cell;
        text-align: right;
        font-size: 7px;
        color: #9CA3AF;
    }

    .page-break { page-break-before: always; }
</style>
</head>
<body>

{{-- ══════════════ HEADER ══════════════ --}}
<div class="header">
    <div class="header-logo">
        <div class="logo-pill">N</div>
    </div>
    <div class="header-text">
        <h1>NutriBase</h1>
        <p>MBG Platform — Laporan Resmi</p>
    </div>
    <div class="header-meta">
        <div class="doc-title">Laporan Terpadu</div>
        <div class="doc-sub">Dicetak: {{ now()->format('d M Y, H:i') }} WIB</div>
    </div>
</div>

{{-- ══════════════ PERIODE ══════════════ --}}
<div class="periode-bar">
    Periode Laporan: <strong>{{ $periodeLabel }}</strong>
    &nbsp;&nbsp;|&nbsp;&nbsp;
    Total Distribusi: <strong>{{ $distribusiData->count() }}</strong>
    &nbsp;&nbsp;|&nbsp;&nbsp;
    Total Penerima: <strong>{{ $penerimaData->count() }}</strong>
</div>

{{-- ══════════════ STATS ══════════════ --}}
@php
    $diterima = $distribusiData->where('status', 'diterima')->count();
    $gagal    = $distribusiData->where('status', 'gagal')->count();
    $pending  = $distribusiData->where('status', 'pending')->count();
    $avgRating = '-';
@endphp
<div class="stats-row">
    <div class="stat-cell">
        <div class="stat-label">Total Distribusi</div>
        <div class="stat-value dark">{{ $distribusiData->count() }}</div>
    </div>
    <div class="stat-cell">
        <div class="stat-label">Diterima</div>
        <div class="stat-value">{{ $diterima }}</div>
    </div>
    <div class="stat-cell">
        <div class="stat-label">Gagal / Pending</div>
        <div class="stat-value danger">{{ $gagal + $pending }}</div>
    </div>
    <div class="stat-cell">
        <div class="stat-label">Total Penerima</div>
        <div class="stat-value dark">{{ $penerimaData->count() }}</div>
    </div>
</div>

{{-- ══════════════ DISTRIBUSI TABLE ══════════════ --}}
<div class="section-title">Data Distribusi</div>
<table>
    <thead>
        <tr>
            <th style="width:24px">No</th>
            <th style="width:60px">Tanggal</th>
            <th style="width:34px">Waktu</th>
            <th>Penerima</th>
            <th>Menu</th>
            <th>Kader</th>
            <th style="width:54px">Jadwal</th>
            <th style="width:50px">Status</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($distribusiData as $i => $d)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $d->waktu_distribusi->format('d M Y') }}</td>
            <td>{{ $d->waktu_distribusi->format('H:i') }}</td>
            <td>{{ $d->penerima->nama ?? '-' }}</td>
            <td>{{ $d->menu->nama ?? '-' }}</td>
            <td>{{ $d->kader->name ?? '-' }}</td>
            <td>{{ $d->jadwal?->tanggal->format('d M Y') ?? '-' }}</td>
            <td>
                <span class="badge badge-{{ $d->status }}">
                    {{ strtoupper($d->status) }}
                </span>
            </td>
            <td>{{ $d->keterangan ?? '-' }}</td>
        </tr>
        @empty
        <tr><td colspan="9" style="text-align:center;color:#9CA3AF;padding:12px;">Tidak ada data distribusi pada periode ini.</td></tr>
        @endforelse
    </tbody>
</table>

{{-- ══════════════ PAGE BREAK ══════════════ --}}
<div class="page-break"></div>

{{-- ══════════════ HEADER REPEAT ══════════════ --}}
<div class="header">
    <div class="header-logo">
        <div class="logo-pill">N</div>
    </div>
    <div class="header-text">
        <h1>NutriBase</h1>
        <p>MBG Platform — Laporan Resmi</p>
    </div>
    <div class="header-meta">
        <div class="doc-title">Data Penerima</div>
        <div class="doc-sub">Periode: {{ $periodeLabel }}</div>
    </div>
</div>

{{-- ══════════════ PENERIMA TABLE ══════════════ --}}
<div class="section-title">Data Penerima</div>
<table>
    <thead>
        <tr>
            <th style="width:24px">No</th>
            <th>Nama</th>
            <th style="width:100px">NIK</th>
            <th style="width:34px">RT</th>
            <th style="width:60px">Kategori</th>
            <th style="width:64px">Est. Selesai</th>
            <th style="width:60px">Terdaftar</th>
        </tr>
    </thead>
    <tbody>
        @forelse($penerimaData as $i => $p)
        @php
            $badgeClass = match($p->kategori) {
                'ibu_hamil'    => 'badge-hamil',
                'ibu_menyusui' => 'badge-menyusui',
                'balita'       => 'badge-balita',
                default        => 'badge-lainnya',
            };
            $kategoriLabel = match($p->kategori) {
                'ibu_hamil'    => 'Ibu Hamil',
                'ibu_menyusui' => 'Ibu Menyusui',
                'balita'       => 'Balita',
                default        => 'Lainnya',
            };
        @endphp
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $p->user->name ?? '-' }}</td>
            <td style="font-family:monospace;font-size:7.5px;letter-spacing:0.5px">{{ $p->nik }}</td>
            <td>RT {{ $p->rt }}</td>
            <td><span class="badge {{ $badgeClass }}">{{ $kategoriLabel }}</span></td>
            <td>{{ $p->estimasi_durasi->format('d M Y') }}</td>
            <td>{{ $p->created_at->format('d M Y') }}</td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;color:#9CA3AF;padding:12px;">Tidak ada data penerima pada periode ini.</td></tr>
        @endforelse
    </tbody>
</table>

{{-- ══════════════ FOOTER ══════════════ --}}
<div class="footer">
    <div class="footer-left">
        NutriBase — MBG Platform &nbsp;|&nbsp; Dokumen ini digenerate secara otomatis oleh sistem
    </div>
    <div class="footer-right">
        {{ now()->format('d M Y, H:i') }} WIB
    </div>
</div>

</body>
</html>