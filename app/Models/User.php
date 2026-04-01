<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
        'nrp',
        'pangkat',
        'nik',
        'email',
        'no_hp',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function kunjungan()
    {
        return $this->hasMany(Kunjungan::class, 'operator_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }

    public function isOperator(): bool
    {
        return $this->role === 'operator';
    }

    public function isPengguna(): bool
    {
        return $this->role === 'pengguna';
    }
}
