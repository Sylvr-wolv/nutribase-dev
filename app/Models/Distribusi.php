<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Distribusi extends Model
{
    protected $table = 'distribusi';

    protected $fillable = [
        'jadwal_id',
        'penerima_id',
        'menu_id',
        'kader_id',
        'waktu_distribusi',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'waktu_distribusi' => 'datetime',
    ];

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    public function penerima(): BelongsTo
    {
        return $this->belongsTo(Penerima::class, 'penerima_id');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function kader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kader_id');
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(Feedback::class, 'distribusi_id');
    }
}
