@extends('layouts.auth')
@section('title', 'Hasil Cek Status')

@section('content')
    <div
        style="min-height:100vh;background:linear-gradient(135deg,#1e3a8a 0%,#1d4ed8 50%,#0ea5e9 100%);display:flex;align-items:center;justify-content:center;padding:2rem 1rem;">
        <div
            style="background:#fff;border-radius:1.5rem;box-shadow:0 20px 60px rgba(0,0,0,0.15);max-width:550px;width:100%;padding:3rem 2rem;">

            <div class="text-center mb-4">
                <h3 class="fw-bold text-dark">Hasil Pencarian</h3>
                <p class="text-muted">Data jadwal kunjungan terbaru Anda berdasarkan pencarian.</p>
            </div>

            <div class="card bg-light border-0 mb-4" style="border-radius:1rem;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">{{ $jadwal->nama_tamu }}</h5>
                    <div class="row g-2 mb-3">
                        <div class="col-sm-5 text-muted small">Tanggal Rencana</div>
                        <div class="col-sm-7 fw-semibold">{{ $jadwal->tanggal_kunjungan->format('d F Y') }}</div>

                        <div class="col-sm-5 text-muted small">Jam Rencana</div>
                        <div class="col-sm-7 fw-semibold">{{ \Carbon\Carbon::parse($jadwal->jam_rencana)->format('H:i') }}
                            WIB</div>

                        <div class="col-sm-5 text-muted small">Tujuan</div>
                        <div class="col-sm-7 fw-semibold">{{ $jadwal->tujuan->nama ?? '—' }}</div>

                        <div class="col-sm-5 text-muted small">Status Jadwal</div>
                        <div class="col-sm-7">
                            @if($jadwal->status === 'Menunggu')
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i
                                        class="bi bi-hourglass-split me-1"></i> Menunggu Persetujuan</span>
                            @elseif($jadwal->status === 'Disetujui')
                                <span class="badge bg-success px-3 py-2 rounded-pill"><i class="bi bi-check-circle me-1"></i>
                                    Telah Disetujui</span>
                            @elseif($jadwal->status === 'Ditolak')
                                <span class="badge bg-danger px-3 py-2 rounded-pill"><i class="bi bi-x-circle me-1"></i>
                                    Ditolak</span>
                            @endif
                        </div>

                        @if($jadwal->status === 'Ditolak')
                            <div class="col-sm-5 text-muted small mt-2">Alasan Penolakan</div>
                            <div class="col-sm-7 text-danger fw-semibold mt-2">{{ $jadwal->catatan ?? '-' }}</div>
                        @endif
                    </div>
                </div>
            </div>

            @if($jadwal->status === 'Disetujui' && $kunjungan && $kunjungan->kode_qr)
                <div class="text-center bg-white border rounded-4 p-4 mb-4 shadow-sm">
                    <h5 class="fw-bold text-success mb-2">QR Code Kunjungan</h5>
                    <p class="text-muted small mb-3">Tunjukkan QR Code ini kepada petugas/mesin scanner saat Anda tiba.</p>
                    <div class="d-inline-block p-3 border rounded bg-white">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($kunjungan->kode_qr) }}"
                            alt="QR Code" class="img-fluid" width="200" height="200">
                    </div>
                    <div class="mt-3 font-monospace text-muted">{{ $kunjungan->kode_qr }}</div>
                </div>
            @endif

            <div class="text-center mt-4">
                <a href="{{ route('welcome') }}" class="btn btn-primary px-4 py-2 rounded-pill fw-semibold"><i
                        class="bi bi-house me-2"></i>Kembali ke Portal</a>
            </div>
        </div>
    </div>
@endsection