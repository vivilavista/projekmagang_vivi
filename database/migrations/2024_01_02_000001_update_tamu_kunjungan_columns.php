<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tambah NIK ke tabel tamu
        Schema::table('tamu', function (Blueprint $table) {
            $table->string('nik', 16)->nullable()->unique()->after('nama');
        });

        // Tambah kode_qr & update status kunjungan
        Schema::table('kunjungan', function (Blueprint $table) {
            $table->string('kode_qr')->nullable()->after('status');
            // Ubah status enum dengan nilai baru
            $table->enum('status', ['Menunggu', 'Disetujui', 'Aktif', 'Selesai'])
                ->default('Menunggu')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('tamu', function (Blueprint $table) {
            $table->dropColumn('nik');
        });

        Schema::table('kunjungan', function (Blueprint $table) {
            $table->dropColumn('kode_qr');
            $table->enum('status', ['Aktif', 'Selesai'])->default('Aktif')->change();
        });
    }
};
