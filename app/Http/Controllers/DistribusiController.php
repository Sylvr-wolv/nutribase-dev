<?php

namespace App\Http\Controllers;

use App\Models\Distribusi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DistribusiController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Distribusi::class);

        $query = Distribusi::with(['jadwal', 'penerima.user', 'menu', 'kader'])->latest();

        if ($request->user()->isPenerima()) {
            $query->whereHas('penerima', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            });
        }

        return response()->json($query->paginate(10));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Distribusi::class);

        $validated = $request->validate([
            'jadwal_id' => ['nullable', 'exists:jadwal,id'],
            'penerima_id' => ['required', 'exists:penerima,id'],
            'menu_id' => ['required', 'exists:menu,id'],
            'waktu_distribusi' => ['required', 'date'],
            'status' => ['required', Rule::in(['diterima', 'gagal', 'pending'])],
            'keterangan' => ['nullable', 'string'],
        ]);

        if (in_array($validated['status'], ['gagal', 'pending'], true) && blank($validated['keterangan'] ?? null)) {
            return response()->json(['message' => 'keterangan wajib jika status gagal/pending'], 422);
        }

        $validated['kader_id'] = $request->user()->id;
        $distribusi = Distribusi::create($validated);

        return response()->json($distribusi, 201);
    }

    public function show(Distribusi $distribusi)
    {
        $this->authorize('view', $distribusi);

        return response()->json($distribusi->load(['jadwal', 'penerima.user', 'menu', 'kader']));
    }

    public function update(Request $request, Distribusi $distribusi)
    {
        $this->authorize('update', $distribusi);

        $validated = $request->validate([
            'jadwal_id' => ['nullable', 'exists:jadwal,id'],
            'penerima_id' => ['sometimes', 'exists:penerima,id'],
            'menu_id' => ['sometimes', 'exists:menu,id'],
            'waktu_distribusi' => ['sometimes', 'date'],
            'status' => ['sometimes', Rule::in(['diterima', 'gagal', 'pending'])],
            'keterangan' => ['nullable', 'string'],
        ]);

        $status = $validated['status'] ?? $distribusi->status;
        $keterangan = $validated['keterangan'] ?? $distribusi->keterangan;
        if (in_array($status, ['gagal', 'pending'], true) && blank($keterangan)) {
            return response()->json(['message' => 'keterangan wajib jika status gagal/pending'], 422);
        }

        $distribusi->update($validated);

        return response()->json($distribusi->fresh());
    }

    public function destroy(Distribusi $distribusi)
    {
        $this->authorize('delete', $distribusi);

        $distribusi->delete();

        return response()->json(['message' => 'Distribusi deleted']);
    }
}
