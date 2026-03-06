@extends('layouts.auth')
@section('title', 'Selamat Datang di Portal Tamu')

@section('content')
    <div
        style="min-height:100vh;background:linear-gradient(135deg,#1e3a8a 0%,#1d4ed8 50%,#0ea5e9 100%);display:flex;align-items:center;justify-content:center;padding:2rem 1rem;">
        <div
            style="background:#fff;border-radius:1.5rem;box-shadow:0 20px 60px rgba(0,0,0,0.15);max-width:800px;width:100%;padding:3rem 2rem;">
            <div class="text-center mb-5">
                <div class="mb-3"
                    style="width:70px;height:70px;border-radius:50%;background:#dbeafe;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                    <i class="bi bi-building text-primary" style="font-size:2rem;"></i>
                </div>
                <h2 class="fw-bold text-dark">Portal Layanan Tamu</h2>
                <p class="text-muted">Selamat datang di Sistem Digital Buku Tamu. Silakan pilih layanan di bawah ini.</p>
            </div>

            <div class="row g-4 justify-content-center">
                <div class="col-md-6">
                    <a href="{{ route('booking.index') }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm"
                            style="border-radius:1rem; transition: transform 0.2s; cursor: pointer;"
                            onmouseover="this.style.transform='translateY(-5px)'"
                            onmouseout="this.style.transform='translateY(0)'">
                            <div class="card-body text-center p-4">
                                <div class="mb-3"
                                    style="width:60px;height:60px;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                                    <i class="bi bi-calendar-plus text-success" style="font-size:1.8rem;"></i>
                                </div>
                                <h5 class="fw-bold text-dark">Buat Jadwal Kunjungan</h5>
                                <p class="text-muted small mb-0">Daftar secara online sebelum datang untuk mendapatkan
                                    persetujuan dan QR Code kunjungan.</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('booking.status') }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm"
                            style="border-radius:1rem; transition: transform 0.2s; cursor: pointer;"
                            onmouseover="this.style.transform='translateY(-5px)'"
                            onmouseout="this.style.transform='translateY(0)'">
                            <div class="card-body text-center p-4">
                                <div class="mb-3"
                                    style="width:60px;height:60px;border-radius:50%;background:#fef3c7;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                                    <i class="bi bi-search text-warning" style="font-size:1.8rem;"></i>
                                </div>
                                <h5 class="fw-bold text-dark">Cek Status Booking</h5>
                                <p class="text-muted small mb-0">Lacak status persetujuan jadwal Anda dan dapatkan QR Code
                                    untuk Check-In.</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="text-center mt-5 pt-4 border-top">
                <p class="text-muted small mb-2">Petugas / Operator / Admin?</p>
                <a href="{{ route('login') }}" class="btn btn-outline-primary px-4 rounded-pill">Masuk ke Dashboard</a>
            </div>
        </div>
    </div>
@endsection