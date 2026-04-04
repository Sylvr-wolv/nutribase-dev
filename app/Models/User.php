<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function isKader(): bool
    {
        return $this->role === 'kader';
    }

    public function isKoordinator(): bool
    {
        return $this->role === 'koordinator';
    }

    public function isPenerima(): bool
    {
        return $this->role === 'penerima';
    }

    public function penerimaProfile(): HasOne
    {
        return $this->hasOne(Penerima::class, 'user_id');
    }

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class, 'kader_id');
    }

    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'kader_id');
    }

    public function distribusis(): HasMany
    {
        return $this->hasMany(Distribusi::class, 'kader_id');
    }

    public function tanggapans(): HasMany
    {
        return $this->hasMany(Tanggapan::class, 'user_id');
    }
}