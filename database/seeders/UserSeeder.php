<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Default Admin
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'nama' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'nrp' => null,
                'pangkat' => null,
            ]
        );

        // Default Petugas
        User::firstOrCreate(
            ['username' => 'petugas01'],
            [
                'nama' => 'Petugas Satu',
                'password' => Hash::make('petugas123'),
                'role' => 'petugas',
                'nrp' => 'NRP-001',
                'pangkat' => 'Brigadir',
            ]
        );

        // Default Operator
        User::firstOrCreate(
            ['username' => 'operator01'],
            [
                'nama' => 'Operator Satu',
                'password' => Hash::make('operator123'),
                'role' => 'operator',
                'nrp' => 'NRP-OP-001',
                'pangkat' => 'Aiptu',
            ]
        );
    }
}
