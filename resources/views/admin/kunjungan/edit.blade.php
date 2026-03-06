@extends('layouts.app')
@section('title', 'Edit Kunjungan')
@section('page-title', 'Edit Kunjungan')

@section('content')
    <div class="card" style="max-width:680px;">
        <div class="card-header py-3">
            <i class="bi bi-pencil-square text-primary me-2"></i>Edit Kunjungan #{{ $kunjungan->id }}
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.kunjungan.update', $kunjungan->id) }}"
                enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tamu</label>
                    <input type="text" class="form-control" value="{{ $kunjungan->tamu->nama ?? '—' }}" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tujuan Kunjungan <span class="text-danger">*</span></label>
                    <textarea name="tujuan" class="form-control @error('tujuan') is-invalid @enderror" rows="3"
                        required>{{ old('tujuan', $kunjungan->tujuan) }}</textarea>
                    @error('tujuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Instansi</label>
                        <input type="text" name="instansi" class="form-control"
                            value="{{ old('instansi', $kunjungan->instansi) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control"
                            value="{{ old('keterangan', $kunjungan->keterangan) }}">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Foto Wajah <small class="text-muted">(kosongkan jika tidak
                            diubah)</small></label>
                    @if($kunjungan->foto_wajah)
                        <div class="mb-2">
                            <img src="{{ Storage::url($kunjungan->foto_wajah) }}" alt="Foto Wajah"
                                style="max-height:100px;border-radius:8px;">
                        </div>
                    @endif
                    <input type="file" name="foto_wajah" class="form-control @error('foto_wajah') is-invalid @enderror"
                        accept="image/*">
                    @error('foto_wajah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                    <a href="{{ route('admin.kunjungan.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection