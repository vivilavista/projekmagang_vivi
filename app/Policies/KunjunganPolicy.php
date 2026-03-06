<?php

namespace App\Policies;

use App\Models\Kunjungan;
use App\Models\User;

class KunjunganPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Kunjungan $kunjungan): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Kunjungan $kunjungan): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $kunjungan->operator_id;
    }

    public function delete(User $user, Kunjungan $kunjungan): bool
    {
        return $user->isAdmin();
    }

    public function checkout(User $user, Kunjungan $kunjungan): bool
    {
        return $kunjungan->isAktif();
    }
}
