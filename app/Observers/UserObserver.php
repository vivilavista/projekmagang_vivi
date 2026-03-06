<?php

namespace App\Observers;

use App\Models\User;
use App\Services\AuditLogService;

class UserObserver
{
    public function __construct(protected AuditLogService $auditLog)
    {
    }

    public function created(User $user): void
    {
        $this->auditLog->log("Created user: {$user->username}", 'users', $user->id);
    }

    public function updated(User $user): void
    {
        $this->auditLog->log("Updated user: {$user->username}", 'users', $user->id);
    }

    public function deleted(User $user): void
    {
        $this->auditLog->log("Deleted user: {$user->username}", 'users', $user->id);
    }
}
