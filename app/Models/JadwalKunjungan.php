<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKunjungan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_kunjungan';

    protected $fillable = [
        'nama_tamu',
        'nik',
        'no_hp',
        'instansi',
        'tujuan_id',
        'keperluan',
        'tanggal_kunjungan',
        'jam_rencana',
        'status',
        'catatan',
        'disetujui_oleh',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
    ];

    public function tujuan()
    {
        return $this->belongsTo(MasterTujuan::class, 'tujuan_id');
    }

    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}
