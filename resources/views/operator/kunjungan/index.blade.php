@extends('layouts.app')
@section('title', 'Kelola Kunjungan')
@section('page-title', 'Data Kunjungan')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <span><i class="bi bi-clipboard-check text-warning me-2"></i>Daftar Kunjungan</span>
        </div>
        <div class="card-body">
            <form method="GET" class="d-flex gap-2 mb-3 flex-wrap">
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control form-control-sm"
                    style="max-width:200px" placeholder="Cari nama tamu...">
                <select name="status" class="form-select form-select-sm" style="max-width:150px">
                    <option value="">Semua Status</option>
                    <option value="Menunggu" {{ ($filters['status'] ?? '') === 'Menunggu' ? 'selected' : '' }}>Menunggu
                    </option>
                    <option value="Aktif" {{ ($filters['status'] ?? '') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Selesai" {{ ($filters['status'] ?? '') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                <button class="btn btn-sm btn-outline-warning"><i class="bi bi-search"></i></button>
            </form>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID Kunjungan</th>
                            <th>ID Tamu</th>
                            <th>Nama Tamu</th>
                            <th>Tujuan</th>
                            <th>Jenis</th>
                            <th>Jam Masuk</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kunjungan as $k)
                                            <tr>
                                                <td class="text-muted fw-semibold">#{{ $k->id }}</td>
                                                <td class="text-muted">#{{ $k->tamu_id }}</td>
                                                <td class="fw-semibold">{{ $k->tamu->nama ?? '—' }}</td>
                                                <td>{{ Str::limit($k->tujuan, 30) }}</td>
                                                <td><span class="badge bg-secondary">{{ $k->jenis_tamu }}</span></td>
                                                <td>{{ $k->jam_masuk?->format('d/m H:i') ?? '—' }}</td>
                                                <td>
                                                    <span class="badge {{ match ($k->status) {
                                'Menunggu' => 'bg-warning text-dark',
                                'Aktif' => 'bg-success',
                                default => 'bg-secondary'
                            } }}">{{ $k->status }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('operator.kunjungan.show', $k->id) }}"
                                                        class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                                                    @if($k->status === 'Menunggu')
                                                        <form action="{{ route('operator.kunjungan.validasi', $k->id) }}" method="POST"
                                                            class="d-inline" onsubmit="return confirm('Validasi?')">
                                                            @csrf
                                                            <button class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i></button>
                                                        </form>
                                                        <form action="{{ route('operator.kunjungan.tolak', $k->id) }}" method="POST"
                                                            class="d-inline" onsubmit="return confirm('Tolak?')">
                                                            @csrf
                                                            <button class="btn btn-sm btn-danger"><i class="bi bi-x-lg"></i></button>
                                                        </form>
                                                    @endif
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