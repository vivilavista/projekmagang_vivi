<?php

namespace App\Services;

use App\Services\AuditLogService;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function __construct(protected AuditLogService $auditLog)
    {
    }

    public function login(array $credentials): bool
    {
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']], $credentials['remember'] ?? false)) {
            request()->session()->regenerate();
            $this->auditLog->log('Login', 'users', Auth::id());

            return true;
        }

        return false;
    }

    public function register(array $data): \App\Models\User
    {
        $user = \App\Models\User::create([
            'nik' => $data['nik'],
            'nama' => $data['nama'],
            'email' => $data['email'],
            'no_hp' => $data['no_hp'],
            'username' => $data['username'],
            'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
            'role' => 'pengguna',
        ]);

        $this->auditLog->log('Register', 'users', $user->id);

        return $user;
    }

    public function logout(): void
    {
        $userId = Auth::id();
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
