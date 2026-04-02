<?php

namespace App\Providers;

use App\Models\Distribusi;
use App\Models\Feedback;
use App\Models\Jadwal;
use App\Models\Menu;
use App\Models\Penerima;
use App\Models\Tanggapan;
use App\Models\User;
use App\Policies\DistribusiPolicy;
use App\Policies\FeedbackPolicy;
use App\Policies\JadwalPolicy;
use App\Policies\MenuPolicy;
use App\Policies\PenerimaPolicy;
use App\Policies\TanggapanPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Penerima::class => PenerimaPolicy::class,
        Menu::class => MenuPolicy::class,
        Jadwal::class => JadwalPolicy::class,
        Distribusi::class => DistribusiPolicy::class,
        Feedback::class => FeedbackPolicy::class,
        Tanggapan::class => TanggapanPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}