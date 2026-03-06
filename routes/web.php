<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Petugas;
use App\Http\Controllers\Operator;
use Illuminate\Support\Facades\Route;

// Root: redirect berdasarkan auth status & role
Route::get('/', function () {
    if (auth()->check()) {
        return match (auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'petugas' => redirect()->route('petugas.dashboard'),
            'operator' => redirect()->route('operator.dashboard'),
            default => redirect()->route('login'),
        };
    }
    return view('welcome');
})->name('welcome');

// ---------------------
//  AUTH ROUTES
// ---------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ---------------------
//  BOOKING PUBLIK (Tanpa Login)
// ---------------------
Route::prefix('booking')->name('booking.')->group(function () {
    Route::get('/', [BookingController::class, 'index'])->name('index');
    Route::post('/', [BookingController::class, 'store'])->name('store');
    Route::get('/status', [BookingController::class, 'status'])->name('status');
    Route::post('/status', [BookingController::class, 'cekStatus'])->name('cek-status');
    Route::get('/{jadwal}/sukses', [BookingController::class, 'sukses'])->name('sukses');
});

// ---------------------
//  BUKTI KUNJUNGAN (Auth)
// ---------------------
Route::middleware('auth')->group(function () {
    Route::get('/kunjungan/{kunjungan}/bukti', function (\App\Models\Kunjungan $kunjungan) {
        return view('kunjungan.bukti', compact('kunjungan'));
    })->name('kunjungan.bukti');
});

// ---------------------
//  ADMIN ROUTES
// ---------------------
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Users
        Route::resource('users', Admin\UserController::class)->except(['show']);

        // Tamu
        Route::resource('tamu', Admin\TamuController::class)->except(['show']);

        // Kunjungan (admin: list, edit, delete only)
        Route::get('/kunjungan', [Admin\KunjunganController::class, 'index'])->name('kunjungan.index');
        Route::get('/kunjungan/{id}/edit', [Admin\KunjunganController::class, 'edit'])->name('kunjungan.edit');
        Route::put('/kunjungan/{id}', [Admin\KunjunganController::class, 'update'])->name('kunjungan.update');
        Route::post('/kunjungan/{id}/checkout', [Admin\KunjunganController::class, 'checkout'])->name('kunjungan.checkout');
        Route::delete('/kunjungan/{id}', [Admin\KunjunganController::class, 'destroy'])->name('kunjungan.destroy');

        // Master Tujuan
        Route::resource('master-tujuan', Admin\MasterTujuanController::class)->except(['show']);

        // Jadwal Kunjungan (Booking)
        Route::get('/jadwal', [Admin\JadwalKunjunganController::class, 'index'])->name('jadwal.index');
        Route::get('/jadwal/kalender-data', [Admin\JadwalKunjunganController::class, 'kalenderData'])->name('jadwal.kalender-data');
        Route::post('/jadwal/{jadwal}/setujui', [Admin\JadwalKunjunganController::class, 'setujui'])->name('jadwal.setujui');
        Route::post('/jadwal/{jadwal}/tolak', [Admin\JadwalKunjunganController::class, 'tolak'])->name('jadwal.tolak');
        Route::delete('/jadwal/{jadwal}', [Admin\JadwalKunjunganController::class, 'destroy'])->name('jadwal.destroy');

        // Scan QR
        Route::get('/scan', [\App\Http\Controllers\ScanController::class, 'index'])->name('scan.index');
        Route::post('/scan/process', [\App\Http\Controllers\ScanController::class, 'process'])->name('scan.process');
    });

// ---------------------
//  PETUGAS ROUTES
// ---------------------
Route::middleware(['auth', 'role:petugas'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {
        Route::get('/dashboard', [Petugas\DashboardController::class, 'index'])->name('dashboard');

        // Kunjungan (petugas: full CRUD + checkout + validasi)
        Route::get('/kunjungan', [Petugas\KunjunganController::class, 'index'])->name('kunjungan.index');
        Route::get('/kunjungan/create', [Petugas\KunjunganController::class, 'create'])->name('kunjungan.create');
        Route::post('/kunjungan', [Petugas\KunjunganController::class, 'store'])->name('kunjungan.store');
        Route::get('/kunjungan/{id}/edit', [Petugas\KunjunganController::class, 'edit'])->name('kunjungan.edit');
        Route::put('/kunjungan/{id}', [Petugas\KunjunganController::class, 'update'])->name('kunjungan.update');
        Route::post('/kunjungan/{id}/checkout', [Petugas\KunjunganController::class, 'checkout'])->name('kunjungan.checkout');
        Route::post('/kunjungan/{id}/validasi', [Petugas\KunjunganController::class, 'validasi'])->name('kunjungan.validasi');

        // Scan QR
        Route::get('/scan', [\App\Http\Controllers\ScanController::class, 'index'])->name('scan.index');
        Route::post('/scan/process', [\App\Http\Controllers\ScanController::class, 'process'])->name('scan.process');
    });

// ---------------------
//  OPERATOR ROUTES
// ---------------------
Route::middleware(['auth', 'role:operator'])
    ->prefix('operator')
    ->name('operator.')
    ->group(function () {
        Route::get('/dashboard', [Operator\DashboardController::class, 'index'])->name('dashboard');

        // Kunjungan: view + validasi only
        Route::get('/kunjungan', [Operator\KunjunganController::class, 'index'])->name('kunjungan.index');
        Route::get('/kunjungan/{id}', [Operator\KunjunganController::class, 'show'])->name('kunjungan.show');
        Route::post('/kunjungan/{id}/validasi', [Operator\KunjunganController::class, 'validasi'])->name('kunjungan.validasi');
        Route::post('/kunjungan/{id}/tolak', [Operator\KunjunganController::class, 'tolak'])->name('kunjungan.tolak');

        // Scan QR
        Route::get('/scan', [\App\Http\Controllers\ScanController::class, 'index'])->name('scan.index');
        Route::post('/scan/process', [\App\Http\Controllers\ScanController::class, 'process'])->name('scan.process');
    });
