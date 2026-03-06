<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kunjungan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kunjungan';

    protected $fillable = [
        'tamu_id',
        'tujuan',
        'jam_masuk',
        'jam_keluar',
        'keterangan',
        'foto_wajah',
        'instansi',
        'operator_id',
        'status',
        'kode_qr',
    ];

    protected $casts = [
        'jam_masuk' => 'datetime',
        'jam_keluar' => 'datetime',
    ];

    public function tamu()
    {
        return $this->belongsTo(Tamu::class, 'tamu_id');
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function isAktif(): bool
    {
        return $this->status === 'Aktif';
    }

    public function isMenunggu(): bool
    {
        return $this->status === 'Menunggu';
    }

    public function isDisetujui(): bool
    {
        return $this->status === 'Disetujui';
    }

    public static function statuses(): array
    {
        return ['Menunggu', 'Disetujui', 'Aktif', 'Selesai'];
    }
}
