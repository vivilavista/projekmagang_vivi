<?php

namespace App\Repositories;

use App\Models\AuditLog;

class AuditLogRepository
{
    public function __construct(protected AuditLog $model)
    {
    }

    public function create(array $data): AuditLog
    {
        return $this->model->create(array_merge($data, [
            'created_at' => now(),
        ]));
    }

    public function paginate(int $perPage = 20)
    {
        return $this->model->with('user')->latest('created_at')->paginate($perPage);
    }
}
