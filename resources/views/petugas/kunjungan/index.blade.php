@extends('layouts.app')
@section('title', 'Data Kunjungan')
@section('page-title', 'Data Kunjungan')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <span><i class="bi bi-door-open text-success me-2"></i>Data Kunjungan Saya</span>
            <a href="{{ route('petugas.kunjungan.create') }}" class="btn btn-success btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Input Kunjungan
            </a>
        </div>
        <div class="card-body">
            <form method="GET" class="d-flex gap-2 mb-3 flex-wrap">
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control form-control-sm"
                    style="max-width:200px" placeholder="Cari tamu...">
                <select name="status" class="form-select form-select-sm" style="max-width:140px">
                    <option value="">Semua</option>
                    <option value="Aktif" {{ ($filters['status'] ?? '') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Selesai" {{ ($filters['status'] ?? '') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                <button class="btn btn-sm btn-outline-success"><i class="bi bi-search"></i></button>
                @if(!empty($filters['search']) || !empty($filters['status']))
                    <a href="{{ route('petugas.kunjungan.index') }}" class="btn btn-sm btn-outline-secondary"><i
                            class="bi bi-x"></i></a>
                @endif
            </form>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Tamu</th>
                            <th>Foto</th>
                            <th>Tujuan</th>
                            <th>Instansi</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Status/QR</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kunjungan as $i => $k)
                            <tr>
                                <td class="text-muted">{{ $kunjungan->firstItem() + $i }}</td>
                                <td class="fw-semibold">{{ $k->tamu->nama ?? '—' }}</td>
                                <td>
                                    @if($k->foto_wajah)
                                        <img src="{{ Storage::url($k->foto_wajah) }}" alt="Foto" style="width:40px;height:40px;object-fit:cover;border-radius:6px;border:1px solid #e5e7eb;">
                                    @else
                                        <div style="width:40px;height:40px;border-radius:6px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;color:#9ca3af;font-size:1.2rem;">
                                            <i class="bi bi-person"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ Str::limit($k->tujuan, 35) }}</td>
                                <td>{{ $k->instansi ?? '—' }}</td>
                                <td style="font-size:0.875rem;">{{ $k->jam_masuk?->format('d/m/Y H:i') }}</td>
                                <td style="font-size:0.875rem;">{{ $k->jam_keluar?->format('d/m/Y H:i') ?? '—' }}</td>
                                <td>
                                    <div class="d-flex flex-column gap-1 align-items-start">
                                        <span class="badge {{ $k->status === 'Aktif' ? 'badge-aktif' : 'badge-selesai' }} px-2">
                                            {{ $k->status }}
                                        </span>
                                        @if($k->kode_qr)
                                            <a href="{{ route('kunjungan.bukti', $k->id) }}" target="_blank" class="text-decoration-none small text-success" title="Lihat QR Code">
                                                <i class="bi bi-qr-code me-1"></i>QR
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <td class="d-flex gap-1">
                                    @if($k->status === 'Aktif')
                                        <form action="{{ route('petugas.kunjungan.checkout', $k->id) }}" method="POST"
                                            onsubmit="return confirm('Checkout?')">
                                            @csrf
                                            <button class="btn btn-sm btn-success" title="Checkout">
                                                <i class="bi bi-box-arrow-in-right"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('petugas.kunjungan.edit', $k->id) }}"
                                        class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Tidak ada data kunjungan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">{{ $kunjungan->links() }}</div>
        </div>
    </div>
@endsection