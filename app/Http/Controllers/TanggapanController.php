<?php

namespace App\Http\Controllers;

use App\Models\Tanggapan;
use Illuminate\Http\Request;

class TanggapanController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Tanggapan::class);

        $query = Tanggapan::with(['feedback.penerima.user', 'feedback.distribusi', 'user'])->latest();

        // Penerima only sees tanggapans on their own feedback
        if ($request->user()->isPenerima()) {
            $query->whereHas('feedback.penerima', fn($q) => $q->where('user_id', $request->user()->id));
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('isi_tanggapan', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($r) => $r->where('name', 'like', "%{$search}%"));
            });
        }

        $tanggapans = $query->paginate(10)->withQueryString();

        $stats = [
            'total'      => Tanggapan::count(),
            'milik_saya' => Tanggapan::where('user_id', $request->user()->id)->count(),
        ];

        return view('tanggapan.index', compact('tanggapans', 'stats'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Tanggapan::class);

        $validated = $request->validate([
            'feedback_id'    => ['required', 'exists:feedback,id'],
            'isi_tanggapan'  => ['required', 'string', 'max:2000'],
        ]);

        $validated['user_id'] = $request->user()->id;

        Tanggapan::create($validated);

        return redirect()->route('feedback.index')->with('success', 'Tanggapan berhasil dikirim.');
    }

    public function update(Request $request, Tanggapan $tanggapan)
    {
        $this->authorize('update', $tanggapan);

        $validated = $request->validate([
            'isi_tanggapan' => ['required', 'string', 'max:2000'],
        ]);

        $tanggapan->update($validated);

        return redirect()->route('tanggapan.index')->with('success', 'Tanggapan berhasil diperbarui.');
    }

    public function destroy(Tanggapan $tanggapan)
    {
        $this->authorize('delete', $tanggapan);

        $tanggapan->delete();

        return redirect()->route('tanggapan.index')->with('success', 'Tanggapan berhasil dihapus.');
    }
}