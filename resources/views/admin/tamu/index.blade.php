@extends('layouts.app')
@section('title', 'Data Tamu')
@section('page-title', 'Data Tamu')

@section('styles')
    <style>
        .avatar-wrap {
            position: relative;
            display: inline-block;
        }

        .avatar-img {
            width: 46px;
            height: 46px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #e5e7eb;
            cursor: pointer;
            transition: transform 0.18s, box-shadow 0.18s;
        }

        .avatar-img:hover {
            transform: scale(1.08);
            box-shadow: 0 4px 16px rgba(5, 35, 85, 0.18);
            border-color: var(--c-light);
        }

        .avatar-placeholder {
            width: 46px;
            height: 46px;
            border-radius: 10px;
            background: linear-gradient(135deg, #f0f5fb 0%, #dbeafe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #93c5fd;
            font-size: 1.4rem;
            border: 2px dashed #bfdbfe;
        }

        .tamu-name {
            font-weight: 600;
            color: #052355;
            line-height: 1.2;
        }

        .tamu-nik {
            font-size: 0.72rem;
            color: #6b7280;
            font-family: 'Courier New', monospace;
            letter-spacing: 0.04em;
            margin-top: 2px;
        }

        .badge-nik {
            font-size: 0.65rem;
            background: #f0f5fb;
            color: #4B76A4;
            border: 1px solid #dbeafe;
            border-radius: 6px;
            padding: 2px 7px;
            font-family: monospace;
            letter-spacing: 0.03em;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 0.85rem;
            transition: all 0.15s;
        }

        .lightbox-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.72);
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

        .prompt-tip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.72rem;
            color: #9ca3af;
            padding: 2px 8px;
            background: #f9fafb;
            border-radius: 20px;
            border: 1px solid #e5e7eb;
        }

        .filter-bar {
            background: #f8fafd;
            border: 1px solid #e9effe;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            margin-bottom: 1rem;
        }

        .table-striped-soft tbody tr:nth-child(even) {
            background: #fafcff;
        }

        .no-data-cell {
            padding: 3rem 0;
        }

        .col-actions {
            min-width: 100px;
        }
    </style>
@endsection

@section('content')

    {{-- Lightbox untuk preview KTP --}}
    <div class="lightbox-overlay" id="lightbox" onclick="closeLightbox()">
        <img id="lightbox-img" src="" alt="Preview KTP">
    </div>

    <div class="card">
        {{-- Card Header --}}
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-person-badge text-primary fs-5"></i>
                <div>
                    <div class="fw-bold" style="color:var(--c-dark);">Daftar Tamu</div>
                    <div class="text-muted" style="font-size:0.72rem;">
                        Kelola data tamu yang berkunjung
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.tamu.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                <i class="bi bi-plus-lg"></i> Tambah Tamu
            </a>
        </div>

        <div class="card-body">

            {{-- Filter / Search Bar --}}
            <div class="filter-bar">
                <form method="GET" class="d-flex gap-2 align-items-center flex-wrap mb-0">
                    <div class="input-group input-group-sm" style="max-width:280px;">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted" style="font-size:0.8rem;"></i>
                        </span>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                            class="form-control border-start-0 ps-0" placeholder="Cari nama, NIK, atau no HP…">
                    </div>
                    <button class="btn btn-sm btn-primary px-3">Cari</button>
                    @if(!empty($filters['search']))
                        <a href="{{ route('admin.tamu.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x me-1"></i>Reset
                        </a>
                    @endif
                    <span class="ms-auto prompt-tip">
                        <i class="bi bi-info-circle"></i>
                        Total {{ $tamuList->total() }} tamu terdaftar
                    </span>
                </form>
            </div>

            {{-- Tabel --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle table-striped-soft mb-0">
                    <thead>
                        <tr>
                            <th style="width:42px;">#</th>
                            <th>
                                <span data-bs-toggle="tooltip" title="Foto KTP tamu (klik untuk perbesar)">
                                    Foto KTP
                                </span>
                            </th>
                            <th>
                                <span data-bs-toggle="tooltip" title="Nama lengkap dan NIK tamu">
                                    Nama &amp; NIK
                                </span>
                            </th>
                            <th>
                                <span data-bs-toggle="tooltip" title="Alamat domisili tamu">
                                    Alamat
                                </span>
                            </th>
                            <th>
                                <span data-bs-toggle="tooltip" title="Nomor handphone yang bisa dihubungi">
                                    No. HP
                                </span>
                            </th>
                            <th>
                                <span data-bs-toggle="tooltip" title="Jumlah kunjungan tamu ini">
                                    Kunjungan
                                </span>
                            </th>
                            <th class="col-actions text-center">
                                <span data-bs-toggle="tooltip" title="Edit atau hapus data tamu">
                                    Aksi
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tamuList as $i => $tamu)
                            <tr>
                                {{-- Nomor Urut --}}
                                <td class="text-muted fw-semibold" style="font-size:0.82rem;">
                                    {{ $tamuList->firstItem() + $i }}
                                </td>

                                {{-- Foto KTP --}}
                                <td>
                                    @if($tamu->foto_ktp)
                                        <div class="avatar-wrap">
                                            <img src="{{ Storage::url($tamu->foto_ktp) }}" alt="KTP {{ $tamu->nama }}"
                                                class="avatar-img" onclick="openLightbox('{{ Storage::url($tamu->foto_ktp) }}')"
                                                data-bs-toggle="tooltip" title="Klik untuk perbesar foto KTP">
                                        </div>
                                    @else
                                        <div class="avatar-placeholder" data-bs-toggle="tooltip" title="Foto KTP belum diunggah">
                                            <i class="bi bi-card-image"></i>
                                        </div>
                                    @endif
                                </td>

                                {{-- Nama & NIK --}}
                                <td>
                                    <div class="tamu-name">{{ $tamu->nama }}</div>
                                    @if($tamu->nik)
                                        <div class="mt-1">
                                            <span class="badge-nik">
                                                <i class="bi bi-person-vcard" style="font-size:0.6rem;"></i>
                                                {{ $tamu->nik }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="tamu-nik text-warning">
                                            <i class="bi bi-exclamation-circle" style="font-size:0.65rem;"></i>
                                            NIK belum diisi
                                        </div>
                                    @endif
                                </td>

                                {{-- Alamat --}}
                                <td>
                                    <span data-bs-toggle="tooltip" title="{{ $tamu->alamat }}" style="cursor:help;">
                                        {{ Str::limit($tamu->alamat, 45) }}
                                    </span>
                                </td>

                                {{-- No HP --}}
                                <td>
                                    <a href="tel:{{ $tamu->no_hp }}"
                                        class="text-decoration-none d-flex align-items-center gap-1"
                                        style="color:var(--c-dark);" data-bs-toggle="tooltip" title="Klik untuk menelepon">
                                        <i class="bi bi-telephone-fill text-success" style="font-size:0.75rem;"></i>
                                        {{ $tamu->no_hp }}
                                    </a>
                                </td>

                                {{-- Jumlah Kunjungan --}}
                                <td class="text-center">
                                    @php $jumlah = $tamu->kunjungan()->count(); @endphp
                                    @if($jumlah > 0)
                                        <span class="badge rounded-pill"
                                            style="background:#dbeafe;color:#1e40af;font-weight:600;font-size:0.8rem;"
                                            data-bs-toggle="tooltip" title="{{ $jumlah }} kali kunjungan">
                                            {{ $jumlah }}×
                                        </span>
                                    @else
                                        <span class="text-muted" style="font-size:0.78rem;">Belum</span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('admin.tamu.edit', $tamu->id) }}"
                                            class="btn btn-action btn-outline-primary" data-bs-toggle="tooltip"
                                            title="Edit data tamu">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.tamu.destroy', $tamu->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Hapus tamu {{ $tamu->nama }}? Data tidak dapat dikembalikan.')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-action btn-outline-danger" data-bs-toggle="tooltip"
                                                title="Hapus tamu">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="no-data-cell text-center">
                                    <div class="d-flex flex-column align-items-center gap-2 text-muted">
                                        <i class="bi bi-person-x" style="font-size:2.5rem;color:#cbd5e1;"></i>
                                        <div class="fw-semibold">Tidak ada data tamu</div>
                                        @if(!empty($filters['search']))
                                            <div style="font-size:0.82rem;">
                                                Tidak ada hasil untuk "{{ $filters['search'] }}"
                                            </div>
                                            <a href="{{ route('admin.tamu.index') }}"
                                                class="btn btn-sm btn-outline-primary mt-1">Reset Pencarian</a>
                                        @else
                                            <a href="{{ route('admin.tamu.create') }}" class="btn btn-sm btn-primary mt-1">
                                                <i class="bi bi-plus-lg me-1"></i>Tambah Tamu Pertama
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tamuList->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted" style="font-size:0.78rem;">
                        Menampilkan {{ $tamuList->firstItem() }}–{{ $tamuList->lastItem() }}
                        dari {{ $tamuList->total() }} tamu
                    </div>
                    {{ $tamuList->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Aktifkan semua tooltip Bootstrap
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el, { trigger: 'hover' });
        });

        // Lightbox KTP
        function openLightbox(src) {
            document.getElementById('lightbox-img').src = src;
            document.getElementById('lightbox').classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        function closeLightbox() {
            document.getElementById('lightbox').classList.remove('show');
            document.getElementsByTagName('body')[0].style.overflow = '';
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeLightbox();
        });
    </script>
@endsection