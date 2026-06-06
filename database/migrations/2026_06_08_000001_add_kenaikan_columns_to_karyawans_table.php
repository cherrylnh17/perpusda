<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            $table->date('tanggal_kenaikan_gaji_berikutnya')
                  ->nullable()
                  ->after('tanggal_mulai_jabatan');

            $table->date('tanggal_kenaikan_jabatan_berikutnya')
                  ->nullable()
                  ->after('tanggal_kenaikan_gaji_berikutnya');
        });
    }

    public function down(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal_kenaikan_gaji_berikutnya',
                'tanggal_kenaikan_jabatan_berikutnya',
            ]);
        });
    }
};
