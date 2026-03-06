@extends('layouts.auth')
@section('title', 'Booking Berhasil')

@section('content')
    <div
        style="min-height:100vh;background:linear-gradient(135deg,#14532d 0%,#16a34a 60%,#4ade80 100%);display:flex;align-items:center;justify-content:center;padding:2rem;">
        <div
            style="background:#fff;border-radius:1.5rem;box-shadow:0 20px 60px rgba(0,0,0,0.15);max-width:560px;width:100%;padding:2.5rem;text-align:center;">
            <div class="mb-3"
                style="width:72px;height:72px;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                <i class="bi bi-check-circle-fill text-success" style="font-size:2rem;"></i>
            </div>
            <h3 class="fw-bold text-dark">Pendaftaran Berhasil!</h3>
            <p class="text-muted">Permintaan kunjungan Anda telah diterima dan sedang menunggu persetujuan.</p>

            <div class="alert alert-info text-start mt-4 mb-4" style="border-radius:1rem;">
                <table class="table table-borderless mb-0 small">
                    <tr>
                        <td class="text-muted fw-semibold" style="width:45%">Nama</td>
                        <td>{{ $jadwal->nama_tamu }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">NIK</td>
                        <td>{{ $jadwal->nik ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Tujuan</td>
                        <td>{{ $jadwal->tujuan?->nama }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Tanggal</td>
                        <td>{{ $jadwal->tanggal_kunjungan->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Jam Rencana</td>
                        <td>{{ \Carbon\Carbon::parse($jadwal->jam_rencana)->format('H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Status</td>
                        <td><span class="badge bg-warning text-dark">{{ $jadwal->status }}</span></td>
                    </tr>
                </table>
            </div>

            {{-- QR Code menggunakan Google Charts API --}}
            <div class="mb-3">
                <p class="text-muted small mb-2">QR Code Kunjungan (tunjukkan saat tiba)</p>
                @php
                    $qrData = urlencode("BOOKING#{$jadwal->id}|{$jadwal->nama_tamu}|{$jadwal->tanggal_kunjungan->format('d/m/Y')}|{$jadwal->jam_rencana}");
                @endphp
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ $qrData }}" alt="QR Code"
                    style="border-radius:12px;border:2px solid #e5e7eb;padding:4px;">
            </div>

            <div class="d-flex gap-2 justify-content-center mt-3">
                <a href="{{ route('booking.index') }}" class="btn btn-outline-success">
                    <i class="bi bi-plus-circle me-1"></i>Booking Lagi
                </a>
                <button onclick="window.print()" class="btn btn-success">
                    <i class="bi bi-printer me-1"></i>Cetak Bukti
                </button>
            </div>
        </div>
    </div>
@endsection