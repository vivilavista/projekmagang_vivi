<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jadwal_kunjungan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tamu');
            $table->string('nik', 16)->nullable();
            $table->string('no_hp', 20);
            $table->string('instansi')->nullable();
            $table->foreignId('tujuan_id')->nullable()->constrained('master_tujuan')->onDelete('set null');
            $table->text('keperluan')->nullable();
            $table->date('tanggal_kunjungan');
            $table->time('jam_rencana');
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');
            $table->text('catatan')->nullable();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_kunjungan');
    }
};
