@extends('layouts.app')
@section('title', 'Master Tujuan')
@section('page-title', 'Master Tujuan Kunjungan')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <span><i class="bi bi-list-ul text-primary me-2"></i>Daftar Tujuan Kunjungan</span>
            <a href="{{ route('admin.master-tujuan.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Tambah
            </a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Tujuan</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tujuanList as $i => $t)
                        <tr>
                            <td class="text-muted px-3">{{ $tujuanList->firstItem() + $i }}</td>
                            <td class="fw-semibold">{{ $t->nama }}</td>
                            <td class="text-muted small">{{ $t->deskripsi ?? '—' }}</td>
                            <td>
                                <span class="badge {{ $t->aktif ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $t->aktif ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.master-tujuan.edit', $t->id) }}"
                                    class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.master-tujuan.destroy', $t->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus tujuan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada data tujuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-end">{{ $tujuanList->links() }}</div>
    </div>
@endsection