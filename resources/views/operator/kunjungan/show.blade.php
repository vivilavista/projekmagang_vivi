@extends('layouts.app')
@section('title', 'Detail Kunjungan')
@section('page-title', 'Detail Kunjungan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <span><i class="bi bi-card-text text-primary me-2"></i>Detail Kunjungan</span>
                    <div class="d-flex gap-2">
                        @auth
                            <a href="{{ route('kunjungan.bukti', $kunjungan->id) }}" target="_blank"
                                class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-printer me-1"></i>Cetak Bukti
                            </a>
                        @endauth
                        <a href="{{ URL::previous() }}" class="btn btn-sm btn-outline-dark">← Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    @include('partials.alerts')

                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted fw-semibold" style="width:38%">ID Kunjungan</td>
                            <td>#{{ str_pad($kunjungan->id, 5, '0', STR_PAD_LEFT) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">ID Tamu</td>
                            <td>#{{ $kunjungan->tamu_id }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Nama Tamu</td>
                            <td class="fw-semibold">{{ $kunjungan->tamu->nama ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">NIK</td>
                            <td>{{ $kunjungan->tamu->nik ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">No HP</td>
                            <td>{{ $kunjungan->tamu->no_hp ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Jenis Tamu</td>
                            <td><span class="badge bg-secondary">{{ $kunjungan->jenis_tamu }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Instansi</td>
                            <td>{{ $kunjungan->instansi ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Tujuan Kunjungan</td>
                            <td>{{ $kunjungan->tujuan }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Keterangan</td>
                            <td>{{ $kunjungan->keterangan ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Jam Masuk</td>
                            <td>{{ $kunjungan->jam_masuk?->format('d/m/Y H:i') ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Jam Keluar</td>
                            <td>{{ $kunjungan->jam_keluar?->format('d/m/Y H:i') ?? 'Belum checkout' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Nama Operator/Petugas</td>
                            <td>{{ $kunjungan->operator->nama ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Status</td>
                            <td>
                                <span class="badge {{ match ($kunjungan->status) {
        'Menunggu' => 'bg-warning text-dark',
        'Disetujui' => 'bg-info text-dark',
        'Aktif' => 'bg-success',
        default => 'bg-secondary'
    } }} fs-6">{{ $kunjungan->status }}</span>
                            </td>
                        </tr>
                        @if($kunjungan->foto_wajah)
                            <tr>
                                <td class="text-muted fw-semibold">Foto Wajah / Selfie</td>
                                <td>
                                    <img src="{{ Storage::url($kunjungan->foto_wajah) }}" alt="Foto Wajah"
                                        style="max-width:140px;border-radius:8px;border:2px solid #e5e7eb;">
                                </td>
                            </tr>
                        @endif
                    </table>

                    @if($kunjungan->status === 'Menunggu')
                        <div class="d-flex gap-2 mt-3">
                            <form action="{{ route('operator.kunjungan.validasi', $kunjungan->id) }}" method="POST"
                                onsubmit="return confirm('Validasi kunjungan ini?')">
                                @csrf
                                <button class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Validasi (Izinkan
                                    Masuk)</button>
                            </form>
                            <form action="{{ route('operator.kunjungan.tolak', $kunjungan->id) }}" method="POST"
                                onsubmit="return confirm('Tolak kunjungan ini?')">
                                @csrf
                                <button class="btn btn-danger"><i class="bi bi-x-lg me-1"></i>Tolak</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection