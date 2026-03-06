@extends('layouts.app')
@section('title', 'Data Kunjungan')
@section('page-title', 'Data Kunjungan')

@section('styles')
    <style>
        .badge-menunggu {
            background: #fef9c3;
            color: #854d0e;
            border: 1px solid #fde68a;
        }

        .badge-disetujui {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .badge-aktif {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }

        .badge-selesai {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
            flex-shrink: 0;
        }

        .dot-menunggu {
            background: #f59e0b;
            box-shadow: 0 0 0 2px #fde68a;
        }

        .dot-disetujui {
            background: #22c55e;
            box-shadow: 0 0 0 2px #bbf7d0;
        }

        .dot-aktif {
            background: #3b82f6;
            box-shadow: 0 0 0 2px #bfdbfe;
            animation: pulse-dot 1.5s ease infinite;
        }

        .dot-selesai {
            background: #9ca3af;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.45;
            }
        }

        .avatar-kunjungan {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid #e5e7eb;
            cursor: pointer;
            transition: transform 0.18s, box-shadow 0.18s;
        }

        .avatar-kunjungan:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 16px rgba(5, 35, 85, 0.2);
        }

        .avatar-placeholder-kunjungan {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: linear-gradient(135deg, #f0f5fb, #e0f2fe);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #93c5fd;
            font-size: 1.3rem;
            border: 2px dashed #bfdbfe;
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
            border-radius: 14px;
            box-shadow: 0 8px 48px rgba(0, 0, 0, 0.5);
        }

        .filter-bar {
            background: #f8fafd;
            border: 1px solid #e9effe;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            margin-bottom: 1rem;
        }

        .tamu-cell-name {
            font-weight: 600;
            color: #052355;
            line-height: 1.2;
        }

        .tamu-cell-sub {
            font-size: 0.72rem;
            color: #6b7280;
            margin-top: 2px;
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

        .time-cell {
            font-size: 0.8rem;
            font-variant-numeric: tabular-nums;
        }

        .time-sub {
            font-size: 0.68rem;
            color: #9ca3af;
        }

        .no-data-cell {
            padding: 3rem 0;
        }
    </style>
@endsection

@section('content')

    {{-- Lightbox foto wajah --}}
    <div class="lightbox-overlay" id="lightbox" onclick="this.classList.remove('show')">
        <img id="lightbox-img" src="" alt="Foto Wajah">
    </div>

    <div class="card">
        {{-- Header --}}
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-door-open text-primary fs-5"></i>
                <div>
                    <div class="fw-bold" style="color:var(--c-dark);">Data Kunjungan</div>
                    <div class="text-muted" style="font-size:0.72rem;">
                        Rekap semua kunjungan tamu masuk &amp; keluar
                    </div>
                </div>
            </div>
            {{-- Ringkasan status --}}
            <div class="d-flex gap-2 flex-wrap">
                <span class="badge badge-aktif" style="font-size:0.7rem;padding:5px 10px;border-radius:20px;">
                    <span class="status-dot dot-aktif"></span>
                    Aktif
                </span>
                <span class="badge badge-menunggu" style="font-size:0.7rem;padding:5px 10px;border-radius:20px;">
                    <span class="status-dot dot-menunggu"></span>
                    Menunggu
                </span>
            </div>
        </div>

        <div class="card-body">

            {{-- Filter Bar --}}
            <div class="filter-bar">
                <form method="GET" class="d-flex gap-2 align-items-center flex-wrap mb-0">
                    <div class="input-group input-group-sm" style="max-width:250px;">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted" style="font-size:0.8rem;"></i>
                        </span>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                            class="form-control border-start-0 ps-0" placeholder="Cari tamu, tujuan, instansi…">
                    </div>
                    <select name="status" class="form-select form-select-sm" style="max-width:150px;"
                        data-bs-toggle="tooltip" title="Filter berdasarkan status kunjungan">
                        <option value="">Semua Status</option>
                        <option value="Menunggu" {{ ($filters['status'] ?? '') === 'Menunggu' ? 'selected' : '' }}>Menunggu
                        </option>
                        <option value="Disetujui" {{ ($filters['status'] ?? '') === 'Disetujui' ? 'selected' : '' }}>Disetujui
                        </option>
                        <option value="Aktif" {{ ($filters['status'] ?? '') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Selesai" {{ ($filters['status'] ?? '') === 'Selesai' ? 'selected' : '' }}>Selesai
                        </option>
                    </select>
                    <button class="btn btn-sm btn-primary px-3">Cari</button>
                    @if(!empty($filters['search']) || !empty($filters['status']))
                        <a href="{{ route('admin.kunjungan.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x me-1"></i>Reset
                        </a>
                    @endif
                    <span class="ms-auto" style="font-size:0.72rem;color:#9ca3af;">
                        <i class="bi bi-info-circle me-1"></i>{{ $kunjungan->total() }} total kunjungan
                    </span>
                </form>
            </div>

            {{-- Tabel --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:38px;">#</th>
                            <th>
                                <span data-bs-toggle="tooltip" title="Foto wajah tamu saat check-in (klik untuk perbesar)">
                                    Foto
                                </span>
                            </th>
                            <th>
                                <span data-bs-toggle="tooltip" title="Nama tamu dan instansi asal">
                                    Tamu &amp; Instansi
                                </span>
                            </th>
                            <th>
                                <span data-bs-toggle="tooltip" title="Tujuan kedatangan tamu">
                                    Tujuan
                                </span>
                            </th>
                            <th>
                                <span data-bs-toggle="tooltip" title="Waktu tamu memasuki gedung">
                                    Jam Masuk
                                </span>
                            </th>
                            <th>
                                <span data-bs-toggle="tooltip" title="Waktu tamu meninggalkan gedung">
                                    Jam Keluar
                                </span>
                            </th>
                            <th>
                                <span data-bs-toggle="tooltip" title="Petugas/operator yang mencatat kunjungan">
                                    Operator
                                </span>
                            </th>
                            <th>
                                <span data-bs-toggle="tooltip"
                                    title="Status kunjungan: Menunggu → Disetujui → Aktif → Selesai">
                                    Status
                                </span>
                            </th>
                            <th class="text-center">
                                <span data-bs-toggle="tooltip" title="Checkout, edit, atau hapus kunjungan">
                                    Aksi
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kunjungan as $i => $k)
                            @php
                                $statusLower = strtolower($k->status);
                                $durasi = null;
                                if ($k->jam_masuk && $k->jam_keluar) {
                                    $menit = $k->jam_masuk->diffInMinutes($k->jam_keluar);
                                    $durasi = $menit >= 60
                                        ? floor($menit / 60) . 'j ' . ($menit % 60) . 'm'
                                        : $menit . 'm';
                                }
                            @endphp
                            <tr>
                                {{-- No --}}
                                <td class="text-muted fw-semibold" style="font-size:0.82rem;">
                                    {{ $kunjungan->firstItem() + $i }}
                                </td>

                                {{-- Foto Wajah --}}
                                <td>
                                    @if($k->foto_wajah)
                                        <img src="{{ Storage::url($k->foto_wajah) }}" alt="Foto {{ $k->tamu->nama ?? '' }}"
                                            class="avatar-kunjungan" data-bs-toggle="tooltip" title="Klik untuk perbesar"
                                            onclick="openLightbox('{{ Storage::url($k->foto_wajah) }}')">
                                    @else
                                        <div class="avatar-placeholder-kunjungan" data-bs-toggle="tooltip"
                                            title="Foto wajah belum diambil">
                                            <i class="bi bi-person"></i>
                                        </div>
                                    @endif
                                </td>

                                {{-- Tamu & Instansi --}}
                                <td>
                                    <div class="tamu-cell-name">{{ $k->tamu->nama ?? '—' }}</div>
                                    @if($k->instansi)
                                        <div class="tamu-cell-sub">
                                            <i class="bi bi-building" style="font-size:0.62rem;"></i>
                                            {{ $k->instansi }}
                                        </div>
                                    @else
                                        <div class="tamu-cell-sub text-muted fst-italic">Perorangan</div>
                                    @endif
                                </td>

                                {{-- Tujuan --}}
                                <td>
                                    <span data-bs-toggle="tooltip" title="{{ $k->tujuan }}">
                                        {{ Str::limit($k->tujuan, 32) }}
                                    </span>
                                    @if($k->keterangan)
                                        <div class="tamu-cell-sub" data-bs-toggle="tooltip" title="{{ $k->keterangan }}">
                                            <i class="bi bi-chat-left-text" style="font-size:0.62rem;"></i>
                                            {{ Str::limit($k->keterangan, 28) }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Jam Masuk --}}
                                <td class="time-cell">
                                    {{ $k->jam_masuk?->format('d/m/Y') }}
                                    <div class="time-sub">{{ $k->jam_masuk?->format('H:i') }}</div>
                                </td>

                                {{-- Jam Keluar --}}
                                <td class="time-cell">
                                    @if($k->jam_keluar)
                                        {{ $k->jam_keluar->format('d/m/Y') }}
                                        <div class="time-sub">
                                            {{ $k->jam_keluar->format('H:i') }}
                                            @if($durasi)
                                                <span class="ms-1" data-bs-toggle="tooltip" title="Durasi kunjungan">
                                                    · {{ $durasi }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic" style="font-size:0.8rem;">Belum keluar</span>
                                    @endif
                                </td>

                                {{-- Operator --}}
                                <td>
                                    <div class="tamu-cell-name" style="font-size:0.85rem;">
                                        {{ $k->operator->nama ?? '—' }}
                                    </div>
                                    @if($k->operator?->pangkat)
                                        <div class="tamu-cell-sub">{{ $k->operator->pangkat }}</div>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge badge-{{ $statusLower }} px-2 py-1 d-flex align-items-center"
                                            style="width:fit-content;border-radius:20px;font-size:0.72rem;font-weight:600;">
                                            <span class="status-dot dot-{{ $statusLower }}"></span>
                                            {{ $k->status }}
                                        </span>
                                        @if($k->kode_qr)
                                            <a href="{{ route('kunjungan.bukti', $k->id) }}" target="_blank"
                                                class="text-decoration-none d-flex align-items-center gap-1"
                                                style="font-size:0.7rem;color:var(--c-mid1);" data-bs-toggle="tooltip"
                                                title="Lihat bukti QR Code kunjungan">
                                                <i class="bi bi-qr-code"></i>
                                                {{ $k->kode_qr }}
                                            </a>
                                        @endif
                                    </div>
                                </td>

                                {{-- Aksi --}}
                                <td>
                                    <div class="d-flex gap-1 justify-content-center">
                                        @if(in_array($k->status, ['Aktif', 'Menunggu', 'Disetujui']))
                                            <form action="{{ route('admin.kunjungan.checkout', $k->id) }}" method="POST"
                                                onsubmit="return confirm('Checkout / selesaikan kunjungan tamu {{ $k->tamu->nama ?? '' }}?')">
                                                @csrf
                                                <button class="btn btn-action btn-outline-success" data-bs-toggle="tooltip"
                                                    title="Checkout — tandai selesai">
                                                    <i class="bi bi-box-arrow-right"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.kunjungan.edit', $k->id) }}"
                                            class="btn btn-action btn-outline-primary" data-bs-toggle="tooltip"
                                            title="Edit kunjungan">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.kunjungan.destroy', $k->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus kunjungan ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-action btn-outline-danger" data-bs-toggle="tooltip"
                                                title="Hapus kunjungan">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="no-data-cell text-center">
                                    <div class="d-flex flex-column align-items-center gap-2 text-muted">
                                        <i class="bi bi-door-closed" style="font-size:2.5rem;color:#cbd5e1;"></i>
                                        <div class="fw-semibold">Tidak ada data kunjungan</div>
                                        @if(!empty($filters['search']) || !empty($filters['status']))
                                            <div style="font-size:0.82rem;">
                                                Tidak ada hasil untuk filter yang dipilih
                                            </div>
                                            <a href="{{ route('admin.kunjungan.index') }}"
                                                class="btn btn-sm btn-outline-primary mt-1">Reset Filter</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($kunjungan->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted" style="font-size:0.78rem;">
                        Menampilkan {{ $kunjungan->firstItem() }}–{{ $kunjungan->lastItem() }}
                        dari {{ $kunjungan->total() }} kunjungan
                    </div>
                    {{ $kunjungan->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el, { trigger: 'hover' });
        });

        function openLightbox(src) {
            document.getElementById('lightbox-img').src = src;
            document.getElementById('lightbox').classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        document.getElementById('lightbox').addEventListener('click', function () {
            this.classList.remove('show');
            document.body.style.overflow = '';
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                document.getElementById('lightbox').classList.remove('show');
                document.body.style.overflow = '';
            }
        });
    </script>
@endsection