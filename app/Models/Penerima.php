<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penerima extends Model
{
    use SoftDeletes;

    protected $table = 'penerima';

    protected $fillable = [
        'user_id',
        'nik',
        'no_telepon',
        'alamat',
        'rt',
        'kategori',
        'deskripsi_kategori',
        'estimasi_durasi',
    ];

    protected $casts = [
        'estimasi_durasi' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function distribusis(): HasMany
    {
        return $this->hasMany(Distribusi::class, 'penerima_id');
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class, 'penerima_id');
    }

    public function getNamaAttribute(): string
    {
        return $this->user->name ?? '';
    }
}
