@extends('layouts.app')
@section('title', 'Dashboard Operator')
@section('page-title', 'Dashboard Operator')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:#fef9c3;">
                        <i class="bi bi-hourglass-split" style="color:#ca8a04;"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Menunggu Validasi</div>
                        <div class="fw-bold fs-3 text-dark">{{ $stats['menunggu'] }}</div>
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
                        <div class="text-muted small">Sedang Berkunjung</div>
                        <div class="fw-bold fs-3 text-dark">{{ $stats['aktif'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:#f3f4f6;">
                        <i class="bi bi-check2-all" style="color:#6b7280;"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Selesai</div>
                        <div class="fw-bold fs-3 text-dark">{{ $stats['selesai'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <span><i class="bi bi-hourglass-split text-warning me-2"></i>Kunjungan Menunggu Validasi</span>
            <a href="{{ route('operator.kunjungan.index') }}" class="btn btn-sm btn-outline-warning">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tamu</th>
                            <th>ID Tamu</th>
                            <th>Tujuan</th>
                            <th>Jenis</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kunjunganMenunggu as $k)
                            <tr>
                                <td class="text-muted">#{{ $k->id }}</td>
                                <td class="fw-semibold">{{ $k->tamu->nama ?? '—' }}</td>
                                <td class="text-muted small">{{ $k->tamu_id }}</td>
                                <td>{{ Str::limit($k->tujuan, 35) }}</td>
                                <td><span class="badge bg-secondary">{{ $k->jenis_tamu ?? 'Masyarakat Umum' }}</span></td>
                                <td class="d-flex gap-1">
                                    <a href="{{ route('operator.kunjungan.show', $k->id) }}"
                                        class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                                    <form action="{{ route('operator.kunjungan.validasi', $k->id) }}" method="POST"
                                        onsubmit="return confirm('Validasi kunjungan ini?')">
                                        @csrf
                                        <button class="btn btn-sm btn-success"><i
                                                class="bi bi-check-lg me-1"></i>Validasi</button>
                                    </form>
                                    <form action="{{ route('operator.kunjungan.tolak', $k->id) }}" method="POST"
                                        onsubmit="return confirm('Tolak kunjungan ini?')">
                                        @csrf
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-x-lg"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Tidak ada kunjungan yang menunggu validasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection