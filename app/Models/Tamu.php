<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tamu extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tamu';

    protected $fillable = [
        'nik',
        'nama',
        'alamat',
        'no_hp',
        'foto_ktp',
    ];

    public function kunjungan()
    {
        return $this->hasMany(Kunjungan::class, 'tamu_id');
    }

    public function kunjunganAktif()
    {
        return $this->kunjungan()->where('status', 'Aktif');
    }
}
