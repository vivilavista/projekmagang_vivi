<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TamuController;
use App\Http\Controllers\Admin\KunjunganController as AdminKunjungan;
use App\Http\Controllers\Admin\MasterTujuanController;
use App\Http\Controllers\Admin\JadwalKunjunganController;
use App\Http\Controllers\Operator\DashboardController as OperatorDashboard;
use App\Http\Controllers\Operator\KunjunganController as OperatorKunjungan;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboard;
use App\Http\Controllers\Petugas\KunjunganController as PetugasKunjungan;
use Illuminate\Support\Facades\Route;

// Root: redirect berdasarkan auth status & role
Route::get('/', function () {
    if (auth()->check()) {
        return match (auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'petugas' => redirect()->route('petugas.dashboard'),
            'operator' => redirect()->route('operator.dashboard'),
            'pengguna' => redirect()->route('booking.index'),
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
    
    // Fitur Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ---------------------
//  BOOKING PUBLIK (Auth Pengguna)
// ---------------------
Route::middleware('auth')->prefix('booking')->name('booking.')->group(function () {
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
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Users
        Route::resource('users', UserController::class)->except(['show']);

        // Tamu
        Route::resource('tamu', TamuController::class)->except(['show']);

        // Kunjungan (admin: list, edit, delete only)
        Route::get('/kunjungan', [AdminKunjungan::class, 'index'])->name('kunjungan.index');
        Route::get('/kunjungan/{id}/edit', [AdminKunjungan::class, 'edit'])->name('kunjungan.edit');
        Route::put('/kunjungan/{id}', [AdminKunjungan::class, 'update'])->name('kunjungan.update');
        Route::post('/kunjungan/{id}/checkout', [AdminKunjungan::class, 'checkout'])->name('kunjungan.checkout');
        Route::delete('/kunjungan/{id}', [AdminKunjungan::class, 'destroy'])->name('kunjungan.destroy');

        // Generate Laporan
        Route::get('/generate-laporan', [AdminKunjungan::class, 'generateLaporan'])->name('laporan.generate');

        // Master Tujuan
        Route::resource('master-tujuan', MasterTujuanController::class)->except(['show']);

        // Jadwal Kunjungan (Booking)
        Route::get('/jadwal', [JadwalKunjunganController::class, 'index'])->name('jadwal.index');
        Route::get('/jadwal/kalender-data', [JadwalKunjunganController::class, 'kalenderData'])->name('jadwal.kalender-data');
        Route::post('/jadwal/{jadwal}/setujui', [JadwalKunjunganController::class, 'setujui'])->name('jadwal.setujui');
        Route::post('/jadwal/{jadwal}/tolak', [JadwalKunjunganController::class, 'tolak'])->name('jadwal.tolak');
        Route::delete('/jadwal/{jadwal}', [JadwalKunjunganController::class, 'destroy'])->name('jadwal.destroy');

        // Scan QR
        Route::get('/scan', [ScanController::class, 'index'])->name('scan.index');
        Route::post('/scan/process', [ScanController::class, 'process'])->name('scan.process');
    });

// ---------------------
//  PETUGAS ROUTES
// ---------------------
Route::middleware(['auth', 'role:petugas'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {
        Route::get('/dashboard', [PetugasDashboard::class, 'index'])->name('dashboard');

        // Kunjungan (petugas: full CRUD + checkout + validasi)
        Route::get('/kunjungan', [PetugasKunjungan::class, 'index'])->name('kunjungan.index');
        Route::get('/kunjungan/create', [PetugasKunjungan::class, 'create'])->name('kunjungan.create');
        Route::post('/kunjungan', [PetugasKunjungan::class, 'store'])->name('kunjungan.store');
        Route::get('/kunjungan/{id}/edit', [PetugasKunjungan::class, 'edit'])->name('kunjungan.edit');
        Route::put('/kunjungan/{id}', [PetugasKunjungan::class, 'update'])->name('kunjungan.update');
        Route::post('/kunjungan/{id}/checkout', [PetugasKunjungan::class, 'checkout'])->name('kunjungan.checkout');
        Route::post('/kunjungan/{id}/validasi', [PetugasKunjungan::class, 'validasi'])->name('kunjungan.validasi');

        // Scan QR
        Route::get('/scan', [ScanController::class, 'index'])->name('scan.index');
        Route::post('/scan/process', [ScanController::class, 'process'])->name('scan.process');
    });

// ---------------------
//  OPERATOR ROUTES
// ---------------------
Route::middleware(['auth', 'role:operator'])
    ->prefix('operator')
    ->name('operator.')
    ->group(function () {
        Route::get('/dashboard', [OperatorDashboard::class, 'index'])->name('dashboard');

        // Kunjungan: view + validasi only
        Route::get('/kunjungan', [OperatorKunjungan::class, 'index'])->name('kunjungan.index');
        Route::get('/kunjungan/{id}', [OperatorKunjungan::class, 'show'])->name('kunjungan.show');
        Route::post('/kunjungan/{id}/validasi', [OperatorKunjungan::class, 'validasi'])->name('kunjungan.validasi');
        Route::post('/kunjungan/{id}/tolak', [OperatorKunjungan::class, 'tolak'])->name('kunjungan.tolak');

        // Scan QR
        Route::get('/scan', [ScanController::class, 'index'])->name('scan.index');
        Route::post('/scan/process', [ScanController::class, 'process'])->name('scan.process');
    });
