<?php

namespace App\Observers;

use App\Models\Tamu;
use App\Services\AuditLogService;

class TamuObserver
{
    public function __construct(protected AuditLogService $auditLog)
    {
    }

    public function created(Tamu $tamu): void
    {
        $this->auditLog->log("Created tamu: {$tamu->nama}", 'tamu', $tamu->id);
    }

    public function updated(Tamu $tamu): void
    {
        $this->auditLog->log("Updated tamu: {$tamu->nama}", 'tamu', $tamu->id);
    }

    public function deleted(Tamu $tamu): void
    {
        $this->auditLog->log("Deleted tamu: {$tamu->nama}", 'tamu', $tamu->id);
    }
}
