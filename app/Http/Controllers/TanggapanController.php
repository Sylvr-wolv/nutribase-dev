<?php

namespace App\Http\Controllers;

use App\Models\Tanggapan;
use Illuminate\Http\Request;

class TanggapanController extends Controller
{
    public function store(Request $request)
    {
        $this->authorize('create', Tanggapan::class);

        $validated = $request->validate([
            'feedback_id'   => ['required', 'exists:feedback,id'],
            'isi_tanggapan' => ['required', 'string', 'max:2000'],
        ]);

        $validated['user_id'] = $request->user()->id;

        Tanggapan::create($validated);

        return redirect()->route('feedback.show', $validated['feedback_id'])
            ->with('success', 'Balasan berhasil dikirim.');
    }

    public function update(Request $request, Tanggapan $tanggapan)
    {
        $this->authorize('update', $tanggapan);

        $validated = $request->validate([
            'isi_tanggapan' => ['required', 'string', 'max:2000'],
        ]);

        $tanggapan->update($validated);

        return redirect()->route('feedback.show', $tanggapan->feedback_id)
            ->with('success', 'Balasan berhasil diperbarui.');
    }

    public function destroy(Tanggapan $tanggapan)
    {
        $this->authorize('delete', $tanggapan);

        $feedbackId = $tanggapan->feedback_id;
        $tanggapan->delete();

        return redirect()->route('feedback.show', $feedbackId)
            ->with('success', 'Balasan berhasil dihapus.');
    }
}