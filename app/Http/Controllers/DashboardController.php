<?php

namespace App\Http\Controllers;

use App\Models\Distribusi;
use App\Models\Feedback;
use App\Models\Menu;
use App\Models\Penerima;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isKader()) {
            return $this->kader();
        }

        if ($user->isKoordinator()) {
            return $this->koordinator();
        }

        // penerima
        return $this->penerima($user);
    }

    // ── KADER ─────────────────────────────────────────────────────────────────

    private function kader()
    {
        // Penerima stats
        $totalPenerima   = Penerima::count();
        $penerimaByKat   = Penerima::select('kategori', DB::raw('count(*) as total'))
                            ->groupBy('kategori')
                            ->pluck('total', 'kategori');

        // Stok stats from menu
        $totalStok       = Menu::sum('stok');
        $menuStok        = Menu::orderByDesc('stok')->take(5)->get();
        $menuHabis       = Menu::where('stok', 0)->count();

        // Distribusi stats
        $totalDistribusi = Distribusi::count();
        $diterima        = Distribusi::where('status', 'diterima')->count();
        $gagal           = Distribusi::where('status', 'gagal')->count();
        $pending         = Distribusi::where('status', 'pending')->count();

        // Recent distribusi
        $recentDistribusi = Distribusi::with(['penerima.user', 'menu', 'kader'])
                            ->latest('waktu_distribusi')
                            ->take(5)
                            ->get();

        // Upcoming jadwal
        $upcomingJadwal = \App\Models\Jadwal::with(['menu', 'kader'])
                            ->where('tanggal', '>=', now()->toDateString())
                            ->orderBy('tanggal')
                            ->take(5)
                            ->get();

        // Feedback belum ditanggapi
        $feedbackPending = Feedback::doesntHave('tanggapans')->count();

        return view('dashboard.kader', compact(
            'totalPenerima', 'penerimaByKat',
            'totalStok', 'menuStok', 'menuHabis',
            'totalDistribusi', 'diterima', 'gagal', 'pending',
            'recentDistribusi', 'upcomingJadwal', 'feedbackPending',
        ));
    }

    // ── KOORDINATOR ───────────────────────────────────────────────────────────

    private function koordinator()
    {
        // Overall stats
        $totalPenerima   = Penerima::count();
        $totalDistribusi = Distribusi::count();
        $diterima        = Distribusi::where('status', 'diterima')->count();
        $gagal           = Distribusi::where('status', 'gagal')->count();
        $pending         = Distribusi::where('status', 'pending')->count();
        $avgRating       = Feedback::avg('rating') ?? 0;
        $totalFeedback   = Feedback::count();

        // Distribusi per day — last 30 days for chart
        $chartData = Distribusi::select(
                DB::raw('DATE(waktu_distribusi) as tanggal'),
                DB::raw('count(*) as total'),
                DB::raw('sum(status = "diterima") as diterima'),
                DB::raw('sum(status = "gagal") as gagal'),
                DB::raw('sum(status = "pending") as pending'),
            )
            ->where('waktu_distribusi', '>=', now()->subDays(29)->startOfDay())
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        // Fill missing days with zeros
        $labels     = [];
        $totals     = [];
        $diterimaArr = [];
        $gagalArr   = [];

        for ($i = 29; $i >= 0; $i--) {
            $date   = now()->subDays($i)->toDateString();
            $day    = $chartData->get($date);
            $labels[]      = Carbon::parse($date)->format('d M');
            $totals[]      = $day ? (int) $day->total    : 0;
            $diterimaArr[] = $day ? (int) $day->diterima : 0;
            $gagalArr[]    = $day ? (int) $day->gagal    : 0;
        }

        // Penerima by kategori for donut
        $penerimaByKat = Penerima::select('kategori', DB::raw('count(*) as total'))
                            ->groupBy('kategori')
                            ->pluck('total', 'kategori');

        // Top kader by distribusi count
        $topKader = User::where('role', 'kader')
                        ->withCount('distribusis')
                        ->orderByDesc('distribusis_count')
                        ->take(5)
                        ->get();

        // Recent feedback
        $recentFeedback = Feedback::with(['penerima.user', 'distribusi.menu'])
                            ->latest()
                            ->take(5)
                            ->get();

        return view('dashboard.koordinator', compact(
            'totalPenerima', 'totalDistribusi',
            'diterima', 'gagal', 'pending',
            'avgRating', 'totalFeedback',
            'labels', 'totals', 'diterimaArr', 'gagalArr',
            'penerimaByKat', 'topKader', 'recentFeedback',
        ));
    }

    // ── PENERIMA ──────────────────────────────────────────────────────────────

    private function penerima(User $user)
    {
        $penerima = $user->penerimaProfile;

        if (! $penerima) {
            return view('dashboard.penerima', [
                'penerima'         => null,
                'totalDistribusi'  => 0,
                'diterima'         => 0,
                'pending'          => 0,
                'gagal'            => 0,
                'latestDistribusi' => collect(),
                'upcomingJadwal'   => collect(),
                'totalFeedback'    => 0,
                'avgRating'        => 0,
                'daysLeft'         => 0,
            ]);
        }

        $penerima->load('user');

        // Distribution stats
        $totalDistribusi = Distribusi::where('penerima_id', $penerima->id)->count();
        $diterima        = Distribusi::where('penerima_id', $penerima->id)->where('status', 'diterima')->count();
        $pending         = Distribusi::where('penerima_id', $penerima->id)->where('status', 'pending')->count();
        $gagal           = Distribusi::where('penerima_id', $penerima->id)->where('status', 'gagal')->count();

        // Latest distribusi
        $latestDistribusi = Distribusi::with(['menu', 'kader'])
                            ->where('penerima_id', $penerima->id)
                            ->latest('waktu_distribusi')
                            ->take(5)
                            ->get();

        // Upcoming jadwal (any jadwal without a specific penerima match — just show upcoming)
        $upcomingJadwal = \App\Models\Jadwal::with(['menu', 'kader'])
                            ->where('tanggal', '>=', now()->toDateString())
                            ->orderBy('tanggal')
                            ->take(3)
                            ->get();

        // Feedback stats
        $totalFeedback   = Feedback::where('penerima_id', $penerima->id)->count();
        $avgRating       = Feedback::where('penerima_id', $penerima->id)->avg('rating') ?? 0;

        // Estimasi durasi countdown
        $daysLeft = (int) now()->diffInDays($penerima->estimasi_durasi, false);

        return view('dashboard.penerima', compact(
            'penerima',
            'totalDistribusi', 'diterima', 'pending', 'gagal',
            'latestDistribusi', 'upcomingJadwal',
            'totalFeedback', 'avgRating',
            'daysLeft',
        ));
    }
}