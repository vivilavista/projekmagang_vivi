<?php

namespace Database\Seeders;

use App\Models\Kunjungan;
use App\Models\Tamu;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KunjunganSeeder extends Seeder
{
    public function run(): void
    {
        $operators = User::whereIn('role', ['operator', 'petugas'])->get();
        $tamuList = Tamu::all();

        if ($tamuList->isEmpty() || $operators->isEmpty()) {
            $this->command->warn('Pastikan TamuSeeder dan UserSeeder sudah dijalankan terlebih dahulu.');
            return;
        }

        $tujuanList = [
            'Bertemu Pimpinan',
            'Mengambil Dokumen',
            'Keperluan Administrasi',
            'Rapat / Koordinasi',
            'Pengaduan / Pelaporan',
            'Kunjungan Kerja',
            'Konsultasi',
            'Lainnya',
        ];

        $instansiList = [
            'Dinas Pendidikan Kota',
            'Kementerian Dalam Negeri',
            'Badan Kepegawaian Daerah',
            'PT. Maju Bersama Tbk',
            'Universitas Negeri Jakarta',
            'Kantor Imigrasi',
            'BPJS Ketenagakerjaan',
            null,
        ];

        $keteranganList = [
            'Membawa surat pengantar dari instansi',
            'Sudah ada perjanjian sebelumnya',
            'Keperluan mendesak',
            'Diminta langsung oleh pimpinan',
            'Membawa rombongan 3 orang',
            null,
        ];

        $kunjunganData = [];
        $now = Carbon::now();

        // 8 kunjungan Selesai (hari-hari lalu)
        for ($i = 0; $i < 8; $i++) {
            $jamMasuk = $now->copy()->subDays(rand(1, 14))->setHour(rand(8, 14))->setMinute(rand(0, 59));
            $jamKeluar = $jamMasuk->copy()->addHours(rand(1, 4))->addMinutes(rand(0, 59));

            $kunjunganData[] = [
                'tamu_id' => $tamuList->random()->id,
                'tujuan' => $tujuanList[array_rand($tujuanList)],
                'instansi' => $instansiList[array_rand($instansiList)],
                'keterangan' => $keteranganList[array_rand($keteranganList)],
                'jam_masuk' => $jamMasuk,
                'jam_keluar' => $jamKeluar,
                'operator_id' => $operators->random()->id,
                'status' => 'Selesai',
                'kode_qr' => Str::upper(Str::random(8)),
                'foto_wajah' => null,
            ];
        }

        // 5 kunjungan Aktif (hari ini)
        for ($i = 0; $i < 5; $i++) {
            $jamMasuk = $now->copy()->subHours(rand(1, 4))->setMinute(rand(0, 59));

            $kunjunganData[] = [
                'tamu_id' => $tamuList->random()->id,
                'tujuan' => $tujuanList[array_rand($tujuanList)],
                'instansi' => $instansiList[array_rand($instansiList)],
                'keterangan' => $keteranganList[array_rand($keteranganList)],
                'jam_masuk' => $jamMasuk,
                'jam_keluar' => null,
                'operator_id' => $operators->random()->id,
                'status' => 'Aktif',
                'kode_qr' => Str::upper(Str::random(8)),
                'foto_wajah' => null,
            ];
        }

        // 4 kunjungan Menunggu (baru saja dibuat)
        for ($i = 0; $i < 4; $i++) {
            $jamMasuk = $now->copy()->subMinutes(rand(5, 60));

            $kunjunganData[] = [
                'tamu_id' => $tamuList->random()->id,
                'tujuan' => $tujuanList[array_rand($tujuanList)],
                'instansi' => $instansiList[array_rand($instansiList)],
                'keterangan' => $keteranganList[array_rand($keteranganList)],
                'jam_masuk' => $jamMasuk,
                'jam_keluar' => null,
                'operator_id' => $operators->random()->id,
                'status' => 'Menunggu',
                'kode_qr' => Str::upper(Str::random(8)),
                'foto_wajah' => null,
            ];
        }

        // 3 kunjungan Disetujui
        for ($i = 0; $i < 3; $i++) {
            $jamMasuk = $now->copy()->subMinutes(rand(60, 180));

            $kunjunganData[] = [
                'tamu_id' => $tamuList->random()->id,
                'tujuan' => $tujuanList[array_rand($tujuanList)],
                'instansi' => $instansiList[array_rand($instansiList)],
                'keterangan' => $keteranganList[array_rand($keteranganList)],
                'jam_masuk' => $jamMasuk,
                'jam_keluar' => null,
                'operator_id' => $operators->random()->id,
                'status' => 'Disetujui',
                'kode_qr' => Str::upper(Str::random(8)),
                'foto_wajah' => null,
            ];
        }

        foreach ($kunjunganData as $data) {
            Kunjungan::create($data);
        }
    }
}
