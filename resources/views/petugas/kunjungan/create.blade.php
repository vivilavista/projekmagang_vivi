@extends('layouts.app')
@section('title', 'Input Kunjungan')
@section('page-title', 'Input Kunjungan Baru')

@section('content')
    <div class="card" style="max-width:700px;">
        <div class="card-header py-3">
            <i class="bi bi-door-open text-success me-2"></i>Form Input Kunjungan
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('petugas.kunjungan.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tamu <span class="text-danger">*</span></label>
                    <select name="tamu_id" class="form-select @error('tamu_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Tamu --</option>
                        @foreach($tamuList as $tamu)
                            <option value="{{ $tamu->id }}" {{ old('tamu_id') == $tamu->id ? 'selected' : '' }}>
                                {{ $tamu->nama }} ({{ $tamu->no_hp }})
                            </option>
                        @endforeach
                    </select>
                    @error('tamu_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tujuan Kunjungan <span class="text-danger">*</span></label>
                    <textarea name="tujuan" class="form-control @error('tujuan') is-invalid @enderror" rows="3" required
                        placeholder="Jelaskan tujuan kunjungan...">{{ old('tujuan') }}</textarea>
                    @error('tujuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Instansi / Asal</label>
                        <input type="text" name="instansi" class="form-control" value="{{ old('instansi') }}"
                            placeholder="Nama instansi/perusahaan">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Jam Masuk</label>
                        <input type="datetime-local" name="jam_masuk" class="form-control"
                            value="{{ old('jam_masuk', now()->format('Y-m-d\TH:i')) }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan') }}"
                        placeholder="Catatan tambahan">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Foto Wajah <small class="text-muted">(max 2MB)</small></label>
                    <input type="file" name="foto_wajah" class="form-control @error('foto_wajah') is-invalid @enderror"
                        accept="image/*" onchange="previewFoto(this)">
                    @error('foto_wajah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div id="fotoPreview" class="mt-2"></div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i>Simpan</button>
                    <a href="{{ route('petugas.kunjungan.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function previewFoto(input) {
            const preview = document.getElementById('fotoPreview');
            preview.innerHTML = '';
            if (input.files && input.files[0]) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(input.files[0]);
                img.style.cssText = 'max-height:120px;border-radius:8px;';
                preview.appendChild(img);
            }
        }
    </script>
@endsection