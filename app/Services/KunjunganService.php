<?php

namespace App\Services;

use App\Repositories\Contracts\KunjunganRepositoryInterface;
use App\Services\AuditLogService;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KunjunganService
{
    public function __construct(
        protected KunjunganRepositoryInterface $repo,
        protected AuditLogService $auditLog
    ) {
    }

    public function paginate(int $perPage = 15, array $filters = [])
    {
        return $this->repo->paginate($perPage, $filters);
    }

    public function findById(int $id)
    {
        return $this->repo->findById($id);
    }

    public function create(array $data, ?UploadedFile $fotoWajah = null)
    {
        $data['operator_id'] = Auth::id();
        $data['jam_masuk'] = $data['jam_masuk'] ?? Carbon::now();
        $data['status'] = 'Aktif';

        if ($fotoWajah) {
            $data['foto_wajah'] = $fotoWajah->store('uploads', 'public');
        }

        $kunjungan = $this->repo->create($data);
        $this->auditLog->log('Create Kunjungan', 'kunjungan', $kunjungan->id);

        return $kunjungan;
    }

    public function update(int $id, array $data, ?UploadedFile $fotoWajah = null)
    {
        $kunjungan = $this->repo->findById($id);

        if ($fotoWajah) {
            if ($kunjungan->foto_wajah) {
                Storage::disk('public')->delete($kunjungan->foto_wajah);
            }
            $data['foto_wajah'] = $fotoWajah->store('uploads', 'public');
        }

        $kunjungan = $this->repo->update($id, $data);
        $this->auditLog->log('Update Kunjungan', 'kunjungan', $kunjungan->id);

        return $kunjungan;
    }

    public function delete(int $id): bool
    {
        $this->auditLog->log('Delete Kunjungan', 'kunjungan', $id);

        return $this->repo->delete($id);
    }

    public function checkout(int $id): bool
    {
        $result = $this->repo->checkout($id);
        $this->auditLog->log('Checkout Kunjungan', 'kunjungan', $id);

        return $result;
    }

    public function getDashboardStats(): array
    {
        return [
            'total_tamu' => \App\Models\Tamu::count(),
            'kunjungan_aktif' => $this->repo->countByStatus('Aktif'),
            'kunjungan_selesai' => $this->repo->countByStatus('Selesai'),
            'kunjungan_hari_ini' => $this->repo->countToday(),
        ];
    }
}
