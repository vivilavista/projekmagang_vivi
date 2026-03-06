@extends('layouts.app')
@section('title', 'Tambah Tamu')
@section('page-title', 'Tambah Tamu')

@section('styles')
    <style>
        .form-section-title {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--c-mid1);
            margin-bottom: 0.75rem;
            padding-bottom: 0.4rem;
            border-bottom: 1px solid #e9effe;
        }

        .field-hint {
            font-size: 0.75rem;
            color: #9ca3af;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .required-note {
            font-size: 0.72rem;
            color: #6b7280;
            margin-bottom: 1rem;
        }

        .ktp-preview-wrap {
            display: none;
            margin-top: 0.5rem;
            position: relative;
            width: fit-content;
        }

        .ktp-preview-wrap img {
            height: 120px;
            border-radius: 10px;
            border: 2px solid #bfdbfe;
            object-fit: cover;
            display: block;
        }

        .ktp-preview-remove {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            cursor: pointer;
            line-height: 1;
        }
    </style>
@endsection

@section('content')
    <div class="card" style="max-width:660px;">
        <div class="card-header py-3 d-flex align-items-center gap-2">
            <i class="bi bi-person-plus text-primary fs-5"></i>
            <div>
                <div class="fw-bold" style="color:var(--c-dark);">Form Tambah Tamu</div>
                <div class="text-muted" style="font-size:0.72rem;">Isi data identitas tamu baru</div>
            </div>
        </div>
        <div class="card-body">
            <div class="required-note">
                <i class="bi bi-asterisk text-danger" style="font-size:0.6rem;"></i>
                Kolom bertanda <span class="text-danger fw-semibold">*</span> wajib diisi.
            </div>

            <form method="POST" action="{{ route('admin.tamu.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Data Identitas --}}
                <div class="form-section-title">
                    <i class="bi bi-person-vcard me-1"></i>Data Identitas
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Nama Lengkap <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                        value="{{ old('nama') }}" placeholder="Contoh: Budi Santoso" required>
                    <div class="field-hint">
                        <i class="bi bi-info-circle"></i>
                        Isi sesuai nama yang tertera di KTP.
                    </div>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        NIK <small class="text-muted fw-normal">(Nomor Induk Kependudukan)</small>
                    </label>
                    <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror"
                        value="{{ old('nik') }}" placeholder="16 digit — cth: 3271012504850001" maxlength="16"
                        pattern="[0-9]{16}">
                    <div class="field-hint">
                        <i class="bi bi-info-circle"></i>
                        16 digit angka sesuai KTP. Kosongkan jika tidak tersedia.
                    </div>
                    @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Kontak --}}
                <div class="form-section-title mt-4">
                    <i class="bi bi-geo-alt me-1"></i>Informasi Kontak
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Alamat <span class="text-danger">*</span>
                    </label>
                    <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3"
                        placeholder="Jl. Contoh No. 1, Kelurahan, Kecamatan, Kota/Kab."
                        required>{{ old('alamat') }}</textarea>
                    <div class="field-hint">
                        <i class="bi bi-info-circle"></i>
                        Isi alamat lengkap termasuk kelurahan dan kecamatan.
                    </div>
                    @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        No. HP / Telepon <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                        <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                            value="{{ old('no_hp') }}" placeholder="Cth: 081234567890" required>
                        @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="field-hint">
                        <i class="bi bi-info-circle"></i>
                        Nomor aktif yang bisa dihubungi.
                    </div>
                </div>

                {{-- Foto KTP --}}
                <div class="form-section-title mt-4">
                    <i class="bi bi-image me-1"></i>Dokumen Foto
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Foto KTP
                        <small class="text-muted fw-normal">(opsional, maks. 2MB)</small>
                    </label>
                    <input type="file" name="foto_ktp" id="foto_ktp_input"
                        class="form-control @error('foto_ktp') is-invalid @enderror" accept="image/*">
                    <div class="ktp-preview-wrap" id="previewWrap">
                        <img id="previewImg" src="" alt="Preview KTP">
                        <button type="button" class="ktp-preview-remove" id="previewRemove" title="Hapus pilihan foto">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                    <div class="field-hint">
                        <i class="bi bi-info-circle"></i>
                        Format JPG, PNG, atau WEBP. Pastikan foto jelas dan terbaca.
                    </div>
                    @error('foto_ktp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2 pt-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan
                    </button>
                    <a href="{{ route('admin.tamu.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Preview foto KTP sebelum upload
        const input = document.getElementById('foto_ktp_input');
        const wrap = document.getElementById('previewWrap');
        const img = document.getElementById('previewImg');
        const remove = document.getElementById('previewRemove');

        input.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    img.src = e.target.result;
                    wrap.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        remove.addEventListener('click', function () {
            input.value = '';
            img.src = '';
            wrap.style.display = 'none';
        });

        // Hanya izinkan angka pada input NIK
        document.querySelector('input[name="nik"]').addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16);
        });
    </script>
@endsection