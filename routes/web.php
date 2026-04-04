<?php

use App\Http\Controllers\DistribusiController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PenerimaController;
use App\Http\Controllers\TanggapanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', [AuthController::class, 'home'])->name('home');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Guest only (tidak boleh sudah login)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store'); 
    Route::get('/tentang', fn() => view('public.tentang'))->name('tentang');
    Route::get('/kontak',  fn() => view('public.kontak'))->name('kontak');
});

/*
|--------------------------------------------------------------------------
| Auth only (harus login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'editProfile'])->name('profile.show');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    Route::resource('users', UserController::class)->except(['create', 'edit']);
    Route::resource('penerima', PenerimaController::class);
    Route::resource('menu', MenuController::class);
    Route::resource('jadwal', JadwalController::class);
    Route::resource('distribusi', DistribusiController::class);
    Route::resource('feedback', FeedbackController::class);
    Route::resource('tanggapan', TanggapanController::class)->except(['create', 'edit']);


    Route::get('laporan/distribusi', [DistribusiController::class, 'index'])->name('laporan.distribusi');
    Route::get('laporan/penerima', [PenerimaController::class, 'index'])->name('laporan.penerima');
    Route::get('laporan/menu', [MenuController::class, 'index'])->name('laporan.menu');
    Route::get('laporan/jadwal', [JadwalController::class, 'index'])->name('laporan.jadwal');
    Route::get('laporan/users', [UserController::class, 'index'])->name('laporan.users');
    Route::resource('feedback', FeedbackController::class)->only(['index', 'show']);
    Route::resource('tanggapan', TanggapanController::class)->except(['create', 'edit']);

    Route::get('riwayat', [DistribusiController::class, 'index'])->name('riwayat');
    Route::get('menu-bantuan', [MenuController::class, 'index'])->name('menu');
    Route::resource('feedback', FeedbackController::class)->only(['index', 'store', 'show']);
    Route::resource('tanggapan', TanggapanController::class)->only(['index', 'show']);
});
