<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Tambah jenis_tamu ke kunjungan
        Schema::table('kunjungan', function (Blueprint $table) {
            $table->enum('jenis_tamu', ['Instansi', 'Masyarakat Umum'])
                ->default('Masyarakat Umum')
                ->after('instansi');
        });

        // Update role enum di users untuk tambah 'operator'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'petugas', 'operator') NOT NULL DEFAULT 'petugas'");
    }

    public function down(): void
    {
        Schema::table('kunjungan', function (Blueprint $table) {
            $table->dropColumn('jenis_tamu');
        });

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'petugas') NOT NULL DEFAULT 'petugas'");
    }
};
