<?php

namespace App\Http\Controllers;

use App\Models\Distribusi;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Feedback::class);

        $query = Feedback::with(['distribusi.menu', 'penerima.user', 'tanggapans.user'])->latest();

        if ($request->user()->isPenerima()) {
            $query->whereHas('penerima', fn($q) => $q->where('user_id', $request->user()->id));
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('penerima.user', fn($r) => $r->where('name', 'like', "%{$search}%"))
                  ->orWhere('isi_ulasan', 'like', "%{$search}%");
            });
        }

        if ($rating = $request->input('rating')) {
            $query->where('rating', $rating);
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $feedbacks = $query->paginate(10)->withQueryString();

        // Stats
        $statsQuery = Feedback::query();
        if ($request->user()->isPenerima()) {
            $statsQuery->whereHas('penerima', fn($q) => $q->where('user_id', $request->user()->id));
        }
        $stats = [
            'total'             => $statsQuery->count(),
            'avg_rating'        => $statsQuery->avg('rating') ?? 0,
            'ditanggapi'        => (clone $statsQuery)->has('tanggapans')->count(),
            'belum_ditanggapi'  => (clone $statsQuery)->doesntHave('tanggapans')->count(),
        ];

        // Distribusi list for create form (penerima: only their own diterima distribusi without feedback yet)
        $distribusiList = collect();
        if ($request->user()->isPenerima()) {
            $penerima = $request->user()->penerimaProfile;
            if ($penerima) {
                $usedIds = Feedback::where('penerima_id', $penerima->id)->pluck('distribusi_id');
                $distribusiList = Distribusi::with('menu')
                    ->where('penerima_id', $penerima->id)
                    ->where('status', 'diterima')
                    ->whereNotIn('id', $usedIds)
                    ->latest('waktu_distribusi')
                    ->get();
            }
        }

        return view('feedback.index', compact('feedbacks', 'stats', 'distribusiList'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Feedback::class);

        $validated = $request->validate([
            'distribusi_id' => ['required', 'exists:distribusi,id', 'unique:feedback,distribusi_id'],
            'penerima_id'   => ['required', 'exists:penerima,id'],
            'rating'        => ['required', 'integer', 'between:1,5'],
            'isi_ulasan'    => ['nullable', 'string', 'max:2000'],
        ]);

        Feedback::create($validated);

        return redirect()->route('feedback.index')->with('success', 'Ulasan berhasil disimpan.');
    }

    public function update(Request $request, Feedback $feedback)
    {
        $this->authorize('update', $feedback);

        $validated = $request->validate([
            'rating'     => ['required', 'integer', 'between:1,5'],
            'isi_ulasan' => ['nullable', 'string', 'max:2000'],
        ]);

        $feedback->update($validated);

        return redirect()->route('feedback.index')->with('success', 'Ulasan berhasil diperbarui.');
    }

    public function destroy(Feedback $feedback)
    {
        $this->authorize('delete', $feedback);

        $feedback->delete();

        return redirect()->route('feedback.index')->with('success', 'Ulasan berhasil dihapus.');
    }
}