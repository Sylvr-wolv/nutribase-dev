<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Feedback::class);

        $query = Feedback::with(['distribusi', 'penerima.user', 'tanggapans.user'])->latest();

        if ($request->user()->isPenerima()) {
            $query->whereHas('penerima', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            });
        }

        return response()->json($query->paginate(10));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Feedback::class);

        $validated = $request->validate([
            'distribusi_id' => ['required', 'exists:distribusi,id', 'unique:feedback,distribusi_id'],
            'penerima_id' => ['required', 'exists:penerima,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'isi_ulasan' => ['nullable', 'string'],
        ]);

        $feedback = Feedback::create($validated);

        return response()->json($feedback, 201);
    }

    public function show(Feedback $feedback)
    {
        $this->authorize('view', $feedback);

        return response()->json($feedback->load(['distribusi', 'penerima.user', 'tanggapans.user']));
    }

    public function update(Request $request, Feedback $feedback)
    {
        $this->authorize('update', $feedback);

        $validated = $request->validate([
            'rating' => ['sometimes', 'integer', 'between:1,5'],
            'isi_ulasan' => ['nullable', 'string'],
        ]);

        $feedback->update($validated);

        return response()->json($feedback->fresh());
    }

    public function destroy(Feedback $feedback)
    {
        $this->authorize('delete', $feedback);

        $feedback->delete();

        return response()->json(['message' => 'Feedback deleted']);
    }
}
