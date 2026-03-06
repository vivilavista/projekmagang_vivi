<?php

namespace App\Services;

use App\Repositories\Contracts\TamuRepositoryInterface;
use App\Services\AuditLogService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TamuService
{
    public function __construct(
        protected TamuRepositoryInterface $repo,
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

    public function create(array $data, ?UploadedFile $fotoKtp = null)
    {
        if ($fotoKtp) {
            $data['foto_ktp'] = $fotoKtp->store('uploads', 'public');
        }

        $tamu = $this->repo->create($data);
        $this->auditLog->log('Create Tamu: ' . $tamu->nama, 'tamu', $tamu->id);

        return $tamu;
    }

    public function update(int $id, array $data, ?UploadedFile $fotoKtp = null)
    {
        $tamu = $this->repo->findById($id);

        if ($fotoKtp) {
            // Remove old file
            if ($tamu->foto_ktp) {
                Storage::disk('public')->delete($tamu->foto_ktp);
            }
            $data['foto_ktp'] = $fotoKtp->store('uploads', 'public');
        }

        $tamu = $this->repo->update($id, $data);
        $this->auditLog->log('Update Tamu: ' . $tamu->nama, 'tamu', $tamu->id);

        return $tamu;
    }

    public function delete(int $id): bool
    {
        $tamu = $this->repo->findById($id);
        $this->auditLog->log('Delete Tamu: ' . $tamu->nama, 'tamu', $tamu->id);

        return $this->repo->delete($id);
    }
}
