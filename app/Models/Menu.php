<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $table = 'menu';

    protected $fillable = [
        'kader_id',
        'nama_menu',
        'deskripsi',
        'stok',
    ];

    protected $casts = [
        'stok' => 'integer',
    ];

    public function kader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kader_id');
    }

    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'menu_id');
    }

    public function distribusis(): HasMany
    {
        return $this->hasMany(Distribusi::class, 'menu_id');
    }
}
