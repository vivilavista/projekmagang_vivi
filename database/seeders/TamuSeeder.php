<?php

namespace Database\Seeders;

use App\Models\Tamu;
use Illuminate\Database\Seeder;

class TamuSeeder extends Seeder
{
    public function run(): void
    {
        $tamuData = [
            [
                'nama' => 'Budi Santoso',
                'nik' => '3271012504850001',
                'alamat' => 'Jl. Kemanggisan Raya No. 12, Palmerah, Jakarta Barat',
                'no_hp' => '081234567890',
                'foto_ktp' => null,
            ],
            [
                'nama' => 'Siti Rahayu',
                'nik' => '3578015512900002',
                'alamat' => 'Jl. Raya Darmo No. 45, Wonokromo, Surabaya',
                'no_hp' => '082198765432',
                'foto_ktp' => null,
            ],
            [
                'nama' => 'Ahmad Fauzi',
                'nik' => '3471020308780003',
                'alamat' => 'Jl. Malioboro No. 67, Gedongtengen, Yogyakarta',
                'no_hp' => '085612345678',
                'foto_ktp' => null,
            ],
            [
                'nama' => 'Dewi Kusuma Wardani',
                'nik' => '3374025609920004',
                'alamat' => 'Jl. Pemuda No. 23, Semarang Tengah, Semarang',
                'no_hp' => '087712345678',
                'foto_ktp' => null,
            ],
            [
                'nama' => 'Rizky Hidayat',
                'nik' => '3273031805950005',
                'alamat' => 'Jl. Sukajadi No. 89, Cidadap, Bandung',
                'no_hp' => '089923456789',
                'foto_ktp' => null,
            ],
            [
                'nama' => 'Eka Pertiwi',
                'nik' => '3175044712880006',
                'alamat' => 'Jl. Veteran No. 10, Gambir, Jakarta Pusat',
                'no_hp' => '081345678901',
                'foto_ktp' => null,
            ],
            [
                'nama' => 'Doni Prasetyo',
                'nik' => '3576021103870007',
                'alamat' => 'Jl. Ahmad Yani No. 33, Mojosari, Mojokerto',
                'no_hp' => '082234567890',
                'foto_ktp' => null,
            ],
            [
                'nama' => 'Yunita Sari',
                'nik' => '3471065008930008',
                'alamat' => 'Perum Condong Catur Blok B2 No. 5, Sleman, DIY',
                'no_hp' => '085698765432',
                'foto_ktp' => null,
            ],
            [
                'nama' => 'Hendra Gunawan',
                'nik' => '3271091206800009',
                'alamat' => 'Jl. Raya Kebayoran Lama No. 201, Kebayoran Lama, Jakarta Selatan',
                'no_hp' => '087856789012',
                'foto_ktp' => null,
            ],
            [
                'nama' => 'Maya Anggraeni',
                'nik' => '3372062301900010',
                'alamat' => 'Jl. Sisingamangaraja No. 14, Salatiga',
                'no_hp' => '089912345678',
                'foto_ktp' => null,
            ],
            [
                'nama' => 'Fajar Nugroho',
                'nik' => '3276030704840011',
                'alamat' => 'Jl. Ciputat Raya No. 55, Pamulang, Tangerang Selatan',
                'no_hp' => '081456789012',
                'foto_ktp' => null,
            ],
            [
                'nama' => 'Indah Permatasari',
                'nik' => '3578061509910012',
                'alamat' => 'Jl. Nginden Semolo No. 22, Sukolilo, Surabaya',
                'no_hp' => '082367890123',
                'foto_ktp' => null,
            ],
            [
                'nama' => 'Teguh Wibowo',
                'nik' => '3471022010760013',
                'alamat' => 'Jl. Kaliurang Km. 7, Ngaglik, Sleman',
                'no_hp' => '085689012345',
                'foto_ktp' => null,
            ],
            [
                'nama' => 'Lestari Handayani',
                'nik' => '3273070302890014',
                'alamat' => 'Jl. BKR No. 101, Bojongloa Kidul, Bandung',
                'no_hp' => '087878901234',
                'foto_ktp' => null,
            ],
            [
                'nama' => 'Agus Setiawan',
                'nik' => '3175050811830015',
                'alamat' => 'Jl. Tanah Abang III No. 8, Tanah Abang, Jakarta Pusat',
                'no_hp' => '081567890123',
                'foto_ktp' => null,
            ],
        ];

        foreach ($tamuData as $data) {
            Tamu::firstOrCreate(
                ['nik' => $data['nik']],
                $data
            );
        }
    }
}
