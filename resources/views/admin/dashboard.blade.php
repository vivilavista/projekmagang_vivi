@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:#dbeafe;">
                        <i class="bi bi-people-fill text-primary"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Tamu Terdaftar</div>
                        <div class="fw-bold fs-3 text-dark">{{ $stats['total_tamu'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
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
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:#f3f4f6;">
                        <i class="bi bi-check2-circle" style="color:#6b7280;"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Kunjungan Selesai</div>
                        <div class="fw-bold fs-3 text-dark">{{ $stats['kunjungan_selesai'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <span><i class="bi bi-clock-history text-primary me-2"></i>Kunjungan Terkini</span>
            <a href="{{ route('admin.kunjungan.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            @php
                $recent = \App\Models\Kunjungan::with('tamu', 'operator')->latest()->take(8)->get();
            @endphp
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tamu</th>
                            <th>Tujuan</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Operator</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent as $k)
                            <tr>
                                <td class="fw-semibold">{{ $k->tamu->nama ?? '-' }}</td>
                                <td>{{ Str::limit($k->tujuan, 40) }}</td>
                                <td>{{ $k->jam_masuk?->format('d/m H:i') }}</td>
                                <td>{{ $k->jam_keluar?->format('d/m H:i') ?? '—' }}</td>
                                <td>{{ $k->operator->nama ?? '-' }}</td>
                                <td>
                                    <span
                                        class="badge {{ $k->status === 'Aktif' ? 'badge-aktif' : 'badge-selesai' }} px-2 py-1">
                                        {{ $k->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada data kunjungan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection