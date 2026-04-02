<?php

namespace App\Http\Controllers;

use App\Models\Tanggapan;
use Illuminate\Http\Request;

class TanggapanController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Tanggapan::class);

        return response()->json(Tanggapan::with(['feedback', 'user'])->latest()->paginate(10));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Tanggapan::class);

        $validated = $request->validate([
            'feedback_id' => ['required', 'exists:feedback,id'],
            'isi_tanggapan' => ['required', 'string'],
        ]);

        $validated['user_id'] = $request->user()->id;
        $tanggapan = Tanggapan::create($validated);

        return response()->json($tanggapan, 201);
    }

    public function show(Tanggapan $tanggapan)
    {
        $this->authorize('view', $tanggapan);

        return response()->json($tanggapan->load(['feedback', 'user']));
    }

    public function update(Request $request, Tanggapan $tanggapan)
    {
        $this->authorize('update', $tanggapan);

        $validated = $request->validate([
            'isi_tanggapan' => ['required', 'string'],
        ]);

        $tanggapan->update($validated);

        return response()->json($tanggapan->fresh());
    }

    public function destroy(Tanggapan $tanggapan)
    {
        $this->authorize('delete', $tanggapan);

        $tanggapan->delete();

        return response()->json(['message' => 'Tanggapan deleted']);
    }
}
