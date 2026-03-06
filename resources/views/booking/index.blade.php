@extends('layouts.auth')
@section('title', 'Booking Kunjungan')

@section('content')
    <div
        style="min-height:100vh;background:linear-gradient(135deg,#1e3a8a 0%,#1d4ed8 50%,#0ea5e9 100%);display:flex;align-items:center;justify-content:center;padding:2rem 1rem;">
        <div
            style="background:#fff;border-radius:1.5rem;box-shadow:0 20px 60px rgba(0,0,0,0.15);max-width:620px;width:100%;padding:2.5rem;">
            <div class="text-center mb-4">
                <div class="mb-3"
                    style="width:56px;height:56px;border-radius:50%;background:#dbeafe;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                    <i class="bi bi-calendar-check text-primary" style="font-size:1.6rem;"></i>
                </div>
                <h3 class="fw-bold text-dark">Booking Kunjungan</h3>
                <p class="text-muted small">Isi formulir berikut untuk mendaftar kunjungan. Kunjungan perlu disetujui
                    terlebih dahulu.</p>
            </div>

            @include('partials.alerts')

            <form method="POST" action="{{ route('booking.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_tamu" class="form-control @error('nama_tamu') is-invalid @enderror"
                            value="{{ old('nama_tamu') }}" required placeholder="Nama sesuai KTP">
                        @error('nama_tamu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">NIK <small class="text-muted">(16 digit)</small></label>
                        <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror"
                            value="{{ old('nik') }}" placeholder="3271xxxxxxxxxxxxxx" maxlength="16">
                        @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">No HP <span class="text-danger">*</span></label>
                        <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                            value="{{ old('no_hp') }}" required placeholder="08xxxxxxxxxx">
                        @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Instansi / Asal</label>
                        <input type="text" name="instansi" class="form-control" value="{{ old('instansi') }}"
                            placeholder="Nama instansi">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Tujuan Kunjungan <span class="text-danger">*</span></label>
                        <select name="tujuan_id" class="form-select @error('tujuan_id') is-invalid @enderror" required>
                            <option value="">-- Pilih tujuan --</option>
                            @foreach($tujuanList as $t)
                                <option value="{{ $t->id }}" {{ old('tujuan_id') == $t->id ? 'selected' : '' }}>{{ $t->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('tujuan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Keperluan / Keterangan</label>
                        <textarea name="keperluan" class="form-control" rows="2"
                            placeholder="Deskripsi singkat keperluan...">{{ old('keperluan') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Kunjungan <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_kunjungan"
                            class="form-control @error('tanggal_kunjungan') is-invalid @enderror"
                            value="{{ old('tanggal_kunjungan') }}" min="{{ date('Y-m-d') }}" required>
                        @error('tanggal_kunjungan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Jam Rencana <span class="text-danger">*</span></label>
                        <input type="time" name="jam_rencana"
                            class="form-control @error('jam_rencana') is-invalid @enderror" value="{{ old('jam_rencana') }}"
                            required>
                        @error('jam_rencana')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-4 py-2 fw-semibold">
                    <i class="bi bi-send me-2"></i>Daftar Kunjungan
                </button>
                <p class="text-center text-muted small mt-3">
                    Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
                </p>
            </form>
        </div>
    </div>

    <!-- Tambahkan CSS & JS Flatpickr khusus untuk Booking -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            flatpickr("input[name='jam_rencana']", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true
            });
        });
    </script>
@endsection