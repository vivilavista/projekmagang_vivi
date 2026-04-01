@extends('layouts.app')

@section('title', 'Laporan Kunjungan')

@section('content')
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="fw-bold mb-0" style="color:var(--c-dark)">
                <i class="bi bi-bar-chart-line me-2"></i>Laporan Kunjungan
            </h4>
            <small class="text-muted">Daftar seluruh kunjungan tamu yang tercatat di sistem</small>
        </div>
        <div class="d-flex gap-2">
            {{-- Placeholder tombol export --}}
            <button class="btn btn-outline-success btn-sm" disabled title="Segera hadir">
                <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
            </button>
            <button class="btn btn-outline-danger btn-sm" disabled title="Segera hadir">
                <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
            </button>
            <a href="{{ route('admin.kunjungan.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    {{-- Tabel Laporan --}}
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between py-3">
            <span><i class="bi bi-table me-1"></i>Data Kunjungan</span>
            <span class="badge bg-primary">{{ $kunjungan->total() }} data</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:60px">#</th>
                            <th>Nama Tamu</th>
                            <th>Tanggal / Jam Masuk</th>
                            <th>Tujuan</th>
                            <th>Instansi</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kunjungan as $k)
                            <tr>
                                <td class="text-center text-muted" style="font-size:.8rem">{{ $k->id }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $k->tamu->nama ?? '-' }}</div>
                                    <small class="text-muted">{{ $k->tamu->no_hp ?? '' }}</small>
                                </td>
                                <td>
                                    <div>{{ \Carbon\Carbon::parse($k->jam_masuk)->format('d M Y') }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($k->jam_masuk)->format('H:i') }} WIB</small>
                                </td>
                                <td>{{ Str::limit($k->tujuan, 60) }}</td>
                                <td>{{ $k->instansi ?? '-' }}</td>
                                <td class="text-center">
                                    @if($k->status === 'Aktif')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Aktif</span>
                                    @elseif($k->status === 'Selesai')
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Selesai</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">{{ $k->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                    Tidak ada data kunjungan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($kunjungan->hasPages())
            <div class="card-footer d-flex justify-content-center border-0 pt-3 pb-3">
                {{ $kunjungan->links() }}
            </div>
        @endif
    </div>
@endsection
