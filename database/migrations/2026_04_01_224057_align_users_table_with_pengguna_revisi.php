<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik')->nullable()->after('pangkat');
            $table->string('email')->nullable()->unique()->after('nik');
            $table->string('no_hp')->nullable()->after('email');
        });

        // Update role enum di users untuk tambah 'pengguna'
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'petugas', 'operator', 'pengguna') NOT NULL DEFAULT 'pengguna'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nik', 'email', 'no_hp']);
        });

        // Kembalikan ke enum sebelumnya
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'petugas', 'operator') NOT NULL DEFAULT 'petugas'");
    }
};
