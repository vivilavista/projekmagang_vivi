@extends('layouts.app')
@section('title', 'Edit Tamu')
@section('page-title', 'Edit Tamu')

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

        .current-ktp {
            position: relative;
            display: inline-block;
            margin-top: 0.5rem;
        }

        .current-ktp img {
            height: 110px;
            border-radius: 10px;
            border: 2px solid #bfdbfe;
            object-fit: cover;
            cursor: pointer;
            transition: transform 0.18s, box-shadow 0.18s;
        }

        .current-ktp img:hover {
            transform: scale(1.04);
            box-shadow: 0 4px 16px rgba(5, 35, 85, 0.18);
        }

        .ktp-label {
            position: absolute;
            bottom: 6px;
            left: 6px;
            background: rgba(0, 0, 0, 0.55);
            color: #fff;
            font-size: 0.6rem;
            padding: 2px 6px;
            border-radius: 4px;
            backdrop-filter: blur(4px);
        }

        .lightbox-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.75);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            cursor: zoom-out;
        }

        .lightbox-overlay.show {
            display: flex;
        }

        .lightbox-overlay img {
            max-width: 90vw;
            max-height: 88vh;
            border-radius: 12px;
            box-shadow: 0 8px 48px rgba(0, 0, 0, 0.5);
        }

        .ktp-new-preview {
            display: none;
            margin-top: 0.5rem;
            position: relative;
            width: fit-content;
        }

        .ktp-new-preview img {
            height: 110px;
            border-radius: 10px;
            border: 2px solid #86efac;
            object-fit: cover;
        }

        .new-badge {
            position: absolute;
            top: -8px;
            left: 6px;
            background: #22c55e;
            color: #fff;
            font-size: 0.6rem;
            padding: 2px 7px;
            border-radius: 10px;
            font-weight: 700;
        }
    </style>
@endsection

@section('content')

    {{-- Lightbox --}}
    <div class="lightbox-overlay" id="lightbox" onclick="this.classList.remove('show')">
        <img id="lightbox-img" src="" alt="Preview KTP">
    </div>

    <div class="card" style="max-width:660px;">
        <div class="card-header py-3 d-flex align-items-center gap-2">
            <i class="bi bi-pencil-square text-primary fs-5"></i>
            <div>
                <div class="fw-bold" style="color:var(--c-dark);">Edit Tamu</div>
                <div class="text-muted" style="font-size:0.72rem;">
                    Mengubah data: <strong>{{ $tamu->nama }}</strong>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.tamu.update', $tamu->id) }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                {{-- Data Identitas --}}
                <div class="form-section-title">
                    <i class="bi bi-person-vcard me-1"></i>Data Identitas
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Nama Lengkap <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                        value="{{ old('nama', $tamu->nama) }}" placeholder="Nama sesuai KTP" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        NIK <small class="text-muted fw-normal">(Nomor Induk Kependudukan)</small>
                    </label>
                    <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror"
                        value="{{ old('nik', $tamu->nik) }}" placeholder="16 digit angka" maxlength="16"
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
                        required>{{ old('alamat', $tamu->alamat) }}</textarea>
                    @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        No. HP / Telepon <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                        <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                            value="{{ old('no_hp', $tamu->no_hp) }}" placeholder="Cth: 081234567890" required>
                        @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Foto KTP --}}
                <div class="form-section-title mt-4">
                    <i class="bi bi-image me-1"></i>Foto KTP
                </div>

                <div class="mb-4">
                    @if($tamu->foto_ktp)
                        <div class="mb-2">
                            <div class="text-muted" style="font-size:0.75rem; margin-bottom: 4px;">
                                <i class="bi bi-check-circle-fill text-success me-1"></i>
                                Foto KTP saat ini (klik untuk perbesar):
                            </div>
                            <div class="current-ktp">
                                <img src="{{ Storage::url($tamu->foto_ktp) }}" alt="Foto KTP {{ $tamu->nama }}"
                                    onclick="document.getElementById('lightbox-img').src=this.src; document.getElementById('lightbox').classList.add('show');">
                                <span class="ktp-label">KTP Tersimpan</span>
                            </div>
                        </div>
                    @endif

                    <label class="form-label fw-semibold mt-2">
                        {{ $tamu->foto_ktp ? 'Ganti Foto KTP' : 'Upload Foto KTP' }}
                        <small class="text-muted fw-normal">(kosongkan jika tidak diubah, maks. 2MB)</small>
                    </label>
                    <input type="file" name="foto_ktp" id="foto_ktp_input"
                        class="form-control @error('foto_ktp') is-invalid @enderror" accept="image/*">
                    <div class="ktp-new-preview" id="newPreviewWrap">
                        <img id="newPreviewImg" src="" alt="Preview Baru">
                        <span class="new-badge">BARU</span>
                    </div>
                    <div class="field-hint">
                        <i class="bi bi-info-circle"></i>
                        Format JPG, PNG, atau WEBP. Pastikan foto jelas dan terbaca.
                    </div>
                    @error('foto_ktp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2 pt-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
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
        document.getElementById('foto_ktp_input').addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('newPreviewImg').src = e.target.result;
                    document.getElementById('newPreviewWrap').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') document.getElementById('lightbox').classList.remove('show');
        });

        // Hanya angka untuk NIK
        document.querySelector('input[name="nik"]').addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16);
        });
    </script>
@endsection