<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jadwal extends Model
{
    protected $table = 'jadwal';

    protected $fillable = [
        'kader_id',
        'menu_id',
        'tanggal',
        'rt',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function kader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kader_id');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function distribusis(): HasMany
    {
        return $this->hasMany(Distribusi::class, 'jadwal_id');
    }
}
