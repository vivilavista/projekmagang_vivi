<?php

namespace Database\Seeders;

use App\Models\MasterTujuan;
use Illuminate\Database\Seeder;

class MasterTujuanSeeder extends Seeder
{
    public function run(): void
    {
        $tujuanList = [
            ['nama' => 'Bertemu Pimpinan', 'deskripsi' => 'Kunjungan untuk bertemu dengan pimpinan instansi'],
            ['nama' => 'Mengambil Dokumen', 'deskripsi' => 'Pengambilan surat, SK, atau dokumen resmi'],
            ['nama' => 'Keperluan Administrasi', 'deskripsi' => 'Urusan administrasi dan persuratan'],
            ['nama' => 'Rapat / Koordinasi', 'deskripsi' => 'Menghadiri rapat atau koordinasi antar instansi'],
            ['nama' => 'Pengaduan / Pelaporan', 'deskripsi' => 'Menyampaikan pengaduan atau laporan'],
            ['nama' => 'Kunjungan Kerja', 'deskripsi' => 'Kunjungan kerja resmi dari instansi lain'],
            ['nama' => 'Konsultasi', 'deskripsi' => 'Konsultasi teknis atau non-teknis'],
            ['nama' => 'Lainnya', 'deskripsi' => 'Keperluan lain di luar kategori di atas'],
        ];

        foreach ($tujuanList as $tujuan) {
            MasterTujuan::firstOrCreate(
                ['nama' => $tujuan['nama']],
                array_merge($tujuan, ['aktif' => true])
            );
        }
    }
}
