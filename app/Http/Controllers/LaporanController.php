<?php

namespace App\Http\Controllers;

use App\Exports\LaporanExport;
use App\Models\Distribusi;
use App\Models\Penerima;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', \App\Models\Distribusi::class);

        [$dateFrom, $dateTo, $distribusiData, $penerimaData] = $this->fetchData($request);

        return view('laporan.index', compact(
            'distribusiData',
            'penerimaData',
            'dateFrom',
            'dateTo',
        ));
    }

    public function download(Request $request)
    {
        $this->authorize('viewAny', \App\Models\Distribusi::class);

        $request->validate([
            'format'    => ['required', 'in:pdf,xlsx,csv'],
            'date_from' => ['nullable', 'date'],
            'date_to'   => ['nullable', 'date'],
        ]);

        [$dateFrom, $dateTo, $distribusiData, $penerimaData] = $this->fetchData($request);

        $periodeLabel = $this->periodeLabel($dateFrom, $dateTo);
        $filename     = 'laporan-nutribase-' . now()->format('Ymd-His');

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('laporan.pdf', compact(
                'distribusiData',
                'penerimaData',
                'periodeLabel',
            ))->setPaper('a4', 'landscape');

            return $pdf->download("{$filename}.pdf");
        }

        return Excel::download(
            new LaporanExport($distribusiData, $penerimaData, $periodeLabel),
            "{$filename}.{$request->format}",
            $request->format === 'xlsx'
                ? \Maatwebsite\Excel\Excel::XLSX
                : \Maatwebsite\Excel\Excel::CSV,
        );
    }

    // ── private helpers ────────────────────────────────────────────────────────

    private function fetchData(Request $request): array
    {
        $dateFrom = $request->input('date_from')
            ? Carbon::parse($request->input('date_from'))->startOfDay()
            : null;

        $dateTo = $request->input('date_to')
            ? Carbon::parse($request->input('date_to'))->endOfDay()
            : null;

        // Distribusi — filter by waktu_distribusi
        $distribusiQuery = Distribusi::with(['penerima.user', 'menu', 'kader'])
            ->latest('waktu_distribusi');

        if ($dateFrom) $distribusiQuery->where('waktu_distribusi', '>=', $dateFrom);
        if ($dateTo)   $distribusiQuery->where('waktu_distribusi', '<=', $dateTo);

        $distribusiData = $distribusiQuery->get();

        // Penerima — filter by created_at (registration date)
        $penerimaQuery = Penerima::with('user')->latest();

        if ($dateFrom) $penerimaQuery->where('created_at', '>=', $dateFrom);
        if ($dateTo)   $penerimaQuery->where('created_at', '<=', $dateTo);

        $penerimaData = $penerimaQuery->get();

        return [$dateFrom, $dateTo, $distribusiData, $penerimaData];
    }

    private function periodeLabel(?Carbon $from, ?Carbon $to): string
    {
        if ($from && $to) {
            return $from->format('d M Y') . ' – ' . $to->format('d M Y');
        }
        if ($from) return 'Sejak ' . $from->format('d M Y');
        if ($to)   return 'Sampai ' . $to->format('d M Y');
        return 'Semua Periode';
    }
}