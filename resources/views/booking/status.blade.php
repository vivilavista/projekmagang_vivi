@extends('layouts.auth')
@section('title', 'Cek Status Booking')

@section('content')
    <div
        style="min-height:100vh;background:linear-gradient(135deg,#1e3a8a 0%,#1d4ed8 50%,#0ea5e9 100%);display:flex;align-items:center;justify-content:center;padding:2rem 1rem;">
        <div
            style="background:#fff;border-radius:1.5rem;box-shadow:0 20px 60px rgba(0,0,0,0.15);max-width:500px;width:100%;padding:3rem 2rem;">
            <div class="text-center mb-4">
                <div class="mb-3"
                    style="width:60px;height:60px;border-radius:50%;background:#fef3c7;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                    <i class="bi bi-search text-warning" style="font-size:1.6rem;"></i>
                </div>
                <h3 class="fw-bold text-dark">Cek Status Kunjungan</h3>
                <p class="text-muted small">Cek persetujuan jadwal Anda dan dapatkan QR Code Akses Masuk.</p>
            </div>

            @include('partials.alerts')

            <form method="POST" action="{{ route('booking.cek-status') }}">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-semibold">Masukkan NIK atau Nomor HP</label>
                    <input type="text" name="identitas"
                        class="form-control form-control-lg @error('identitas') is-invalid @enderror"
                        value="{{ old('identitas') }}" required placeholder="Contoh: 3271xxx atau 0812xxx">
                    @error('identitas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-warning w-100 py-2 fw-semibold text-dark">
                    <i class="bi bi-search me-2"></i>Cek Status
                </button>
            </form>

            <div class="text-center mt-4 pt-4 border-top">
                <a href="{{ route('welcome') }}" class="text-decoration-none text-muted small"><i
                        class="bi bi-arrow-left me-1"></i>Kembali ke Portal Utama</a>
            </div>
        </div>
    </div>
@endsection