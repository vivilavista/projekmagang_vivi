<?php

namespace App\Observers;

use App\Models\Kunjungan;
use App\Services\AuditLogService;

class KunjunganObserver
{
    public function __construct(protected AuditLogService $auditLog)
    {
    }

    public function creating(Kunjungan $kunjungan): void
    {
        // Generate unique QR Code ID
        if (empty($kunjungan->kode_qr)) {
            $kunjungan->kode_qr = 'QR-' . strtoupper(uniqid()) . '-' . time();
        }
    }

    public function created(Kunjungan $kunjungan): void
    {
        $this->auditLog->log("Created kunjungan ID#{$kunjungan->id}", 'kunjungan', $kunjungan->id);
    }

    public function updated(Kunjungan $kunjungan): void
    {
        $action = $kunjungan->status === 'Selesai' ? 'Checkout kunjungan' : 'Updated kunjungan';
        $this->auditLog->log("{$action} ID#{$kunjungan->id}", 'kunjungan', $kunjungan->id);
    }

    public function deleted(Kunjungan $kunjungan): void
    {
        $this->auditLog->log("Deleted kunjungan ID#{$kunjungan->id}", 'kunjungan', $kunjungan->id);
    }
}
