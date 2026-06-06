<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])
                  ->nullable()
                  ->after('nik');

            $table->date('tanggal_lahir')
                  ->nullable()
                  ->after('jenis_kelamin');
        });
    }

    public function down(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            $table->dropColumn(['jenis_kelamin', 'tanggal_lahir']);
        });
    }
};
