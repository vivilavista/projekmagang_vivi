@extends('layouts.app')
@section('title', 'Kelola User')
@section('page-title', 'Kelola User')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <span><i class="bi bi-people text-primary me-2"></i>Daftar User</span>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Tambah User
            </a>
        </div>
        <div class="card-body">
            <form method="GET" class="d-flex gap-2 mb-3">
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control form-control-sm"
                    placeholder="Cari nama / username...">
                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-search"></i></button>
                @if(!empty($filters['search']))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary"><i
                            class="bi bi-x"></i></a>
                @endif
            </form>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>NRP</th>
                            <th>Pangkat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $i => $user)
                            <tr>
                                <td class="text-muted">{{ $users->firstItem() + $i }}</td>
                                <td class="fw-semibold">{{ $user->nama }}</td>
                                <td><code>{{ $user->username }}</code></td>
                                <td>
                                    <span class="badge {{ $user->role === 'admin' ? 'bg-primary' : 'bg-success' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>{{ $user->nrp ?? '—' }}</td>
                                <td>{{ $user->pangkat ?? '—' }}</td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                        class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Hapus user {{ $user->username }}?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Tidak ada data user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">{{ $users->links() }}</div>
        </div>
    </div>
@endsection