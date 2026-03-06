@extends('layouts.app')
@section('title', 'Dashboard Petugas')
@section('page-title', 'Dashboard')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:#dbeafe;">
                        <i class="bi bi-calendar-day text-primary"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Kunjungan Hari Ini</div>
                        <div class="fw-bold fs-3 text-dark">{{ $stats['kunjungan_hari_ini'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:#dcfce7;">
                        <i class="bi bi-door-open-fill" style="color:#16a34a;"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Kunjungan Aktif</div>
                        <div class="fw-bold fs-3 text-dark">{{ $stats['kunjungan_aktif'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <span><i class="bi bi-door-open text-success me-2"></i>Kunjungan Aktif Saya</span>
            <a href="{{ route('petugas.kunjungan.create') }}" class="btn btn-success btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Input Kunjungan
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tamu</th>
                            <th>Tujuan</th>
                            <th>Jam Masuk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kunjunganAktif as $k)
                            <tr>
                                <td class="fw-semibold">{{ $k->tamu->nama ?? '—' }}</td>
                                <td>{{ Str::limit($k->tujuan, 40) }}</td>
                                <td>{{ $k->jam_masuk?->format('H:i') }}</td>
                                <td>
                                    <form action="{{ route('petugas.kunjungan.checkout', $k->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Checkout tamu {{ $k->tamu->nama ?? '' }}?')">
                                        @csrf
                                        <button class="btn btn-sm btn-success"><i
                                                class="bi bi-box-arrow-in-right me-1"></i>Checkout</button>
                                    </form>
                                    <a href="{{ route('petugas.kunjungan.edit', $k->id) }}"
                                        class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Tidak ada kunjungan aktif.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection