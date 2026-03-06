<?php

namespace App\Services;

use App\Repositories\AuditLogRepository;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    public function __construct(protected AuditLogRepository $repo)
    {
    }

    public function log(string $aktivitas, string $tabel, ?int $dataId = null): void
    {
        $this->repo->create([
            'user_id' => Auth::id(),
            'aktivitas' => $aktivitas,
            'tabel' => $tabel,
            'data_id' => $dataId,
        ]);
    }
}
