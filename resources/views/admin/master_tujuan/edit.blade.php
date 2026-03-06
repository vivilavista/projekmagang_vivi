@extends('layouts.app')
@section('title', 'Edit Tujuan')
@section('page-title', 'Edit Tujuan Kunjungan')

@section('content')
    <div class="card" style="max-width:560px;">
        <div class="card-header py-3"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Tujuan:
            {{ $masterTujuan->nama }}</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.master-tujuan.update', $masterTujuan->id) }}">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Tujuan <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                        value="{{ old('nama', $masterTujuan->nama) }}" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control"
                        rows="3">{{ old('deskripsi', $masterTujuan->deskripsi) }}</textarea>
                </div>
                <div class="mb-4 form-check">
                    <input type="hidden" name="aktif" value="0">
                    <input type="checkbox" name="aktif" value="1" class="form-check-input" id="aktif" {{ old('aktif', $masterTujuan->aktif) ? 'checked' : '' }}>
                    <label class="form-check-label" for="aktif">Aktif</label>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                    <a href="{{ route('admin.master-tujuan.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection