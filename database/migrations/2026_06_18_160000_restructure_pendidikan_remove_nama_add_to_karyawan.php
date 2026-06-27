<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus kolom nama_pendidikan dari tabel pendidikans
        Schema::table('pendidikans', function (Blueprint $table) {
            $table->dropColumn('nama_pendidikan');
        });

        // Tambah kolom nama_pendidikan ke tabel karyawans (free text)
        Schema::table('karyawans', function (Blueprint $table) {
            $table->string('nama_pendidikan', 100)->nullable()->after('id_pendidikan');
        });
    }

    public function down(): void
    {
        // Rollback: kembalikan nama_pendidikan ke pendidikans
        Schema::table('pendidikans', function (Blueprint $table) {
            $table->string('nama_pendidikan', 100)->nullable()->after('jenjang');
        });

        // Hapus nama_pendidikan dari karyawans
        Schema::table('karyawans', function (Blueprint $table) {
            $table->dropColumn('nama_pendidikan');
        });
    }
};
