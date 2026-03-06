<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kunjungan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tamu_id')->constrained('tamu')->onDelete('cascade');
            $table->string('tujuan');
            $table->dateTime('jam_masuk');
            $table->dateTime('jam_keluar')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('foto_wajah')->nullable();
            $table->string('instansi')->nullable();
            $table->foreignId('operator_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['Aktif', 'Selesai'])->default('Aktif');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungan');
    }
};
