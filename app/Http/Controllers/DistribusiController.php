<?php

namespace App\Http\Controllers;

use App\Models\Distribusi;
use App\Models\Jadwal;
use App\Models\Menu;
use App\Models\Penerima;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DistribusiController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Distribusi::class);

        $query = Distribusi::with(['jadwal.menu', 'penerima', 'menu', 'kader'])->latest('waktu_distribusi');

        if ($request->user()->isPenerima()) {
            $query->whereHas('penerima', fn($q) => $q->where('user_id', $request->user()->id));
        }

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('penerima', fn($r) => $r->where('nama', 'like', "%{$search}%"))
                  ->orWhereHas('menu',     fn($r) => $r->where('nama', 'like', "%{$search}%"))
                  ->orWhereHas('kader',    fn($r) => $r->where('name', 'like', "%{$search}%"));
            });
        }

        // Filter status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Filter date range
        if ($from = $request->input('date_from')) {
            $query->whereDate('waktu_distribusi', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $query->whereDate('waktu_distribusi', '<=', $to);
        }

        $distribusis = $query->paginate(10)->withQueryString();

        // Stats (unfiltered totals for the current user scope)
        $statsQuery = Distribusi::query();
        if ($request->user()->isPenerima()) {
            $query->whereHas('penerima', fn($q) => $q->where('user_id', $request->user()->id));
        }
        $distribusiStats = [
            'total'    => $statsQuery->count(),
            'diterima' => (clone $statsQuery)->where('status', 'diterima')->count(),
            'gagal'    => (clone $statsQuery)->where('status', 'gagal')->count(),
            'pending'  => (clone $statsQuery)->where('status', 'pending')->count(),
        ];

        $penerimas = Penerima::with('user')->get()->sortBy(fn($p) => $p->user->name)->values();
        $menus = Menu::orderBy('nama_menu')->get();
        $jadwals   = Jadwal::with('menu')->orderByDesc('tanggal')->get();

        return view('distribusi.index', compact(
            'distribusis', 'distribusiStats', 'penerimas', 'menus', 'jadwals'
        ));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Distribusi::class);

        $validated = $request->validate([
            'jadwal_id'        => ['nullable', 'exists:jadwal,id'],
            'penerima_id'      => ['required', 'exists:penerima,id'],
            'menu_id'          => ['required', 'exists:menu,id'],
            'waktu_distribusi' => ['required', 'date'],
            'status'           => ['required', Rule::in(['diterima', 'gagal', 'pending'])],
            'keterangan'       => ['nullable', 'string', 'max:1000'],
        ]);

        if (in_array($validated['status'], ['gagal', 'pending'], true) && blank($validated['keterangan'] ?? null)) {
            return back()->withErrors(['keterangan' => 'Keterangan wajib diisi jika status gagal atau pending.'])->withInput();
        }

        $validated['kader_id'] = $request->user()->id;

        Distribusi::create($validated);

        return redirect()->route('distribusi.index')->with('success', 'Data distribusi berhasil ditambahkan.');
    }

    public function update(Request $request, Distribusi $distribusi)
    {
        $this->authorize('update', $distribusi);

        $validated = $request->validate([
            'jadwal_id'        => ['nullable', 'exists:jadwal,id'],
            'penerima_id'      => ['required', 'exists:penerima,id'],
            'menu_id'          => ['required', 'exists:menu,id'],
            'waktu_distribusi' => ['required', 'date'],
            'status'           => ['required', Rule::in(['diterima', 'gagal', 'pending'])],
            'keterangan'       => ['nullable', 'string', 'max:1000'],
        ]);

        if (in_array($validated['status'], ['gagal', 'pending'], true) && blank($validated['keterangan'] ?? null)) {
            return back()->withErrors(['keterangan' => 'Keterangan wajib diisi jika status gagal atau pending.'])->withInput();
        }

        $distribusi->update($validated);

        return redirect()->route('distribusi.index')->with('success', 'Data distribusi berhasil diperbarui.');
    }

    public function destroy(Distribusi $distribusi)
    {
        $this->authorize('delete', $distribusi);

        $distribusi->delete();

        return redirect()->route('distribusi.index')->with('success', 'Data distribusi berhasil dihapus.');
    }
}