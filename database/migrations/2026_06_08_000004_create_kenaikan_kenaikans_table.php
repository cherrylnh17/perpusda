<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi_kenaikans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('karyawan_id')
                  ->constrained('karyawans')
                  ->cascadeOnDelete();

            // Membedakan notifikasi gaji atau jabatan
            $table->enum('tipe', ['gaji', 'jabatan']);

            // Tanggal kenaikan yang dipantau
            $table->date('tanggal_kenaikan');

            // false  = popup masih muncul di dashboard
            // true   = admin sudah menutup / memproses notifikasi
            $table->boolean('sudah_dibaca')->default(false);

            $table->timestamps();

            // Satu karyawan hanya boleh punya 1 notifikasi aktif per tipe
            $table->unique(['karyawan_id', 'tipe', 'tanggal_kenaikan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi_kenaikans');
    }
};
