<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feedback extends Model
{
    protected $table = 'feedback';

    protected $fillable = [
        'distribusi_id',
        'penerima_id',
        'rating',
        'isi_ulasan',
        'gambar',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function distribusi(): BelongsTo
    {
        return $this->belongsTo(Distribusi::class, 'distribusi_id');
    }

    public function penerima(): BelongsTo
    {
        return $this->belongsTo(Penerima::class, 'penerima_id');
    }

    public function tanggapans(): HasMany
    {
        return $this->hasMany(Tanggapan::class, 'feedback_id');
    }
}
