<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kenaikan_jabatans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('karyawan_id')
                  ->constrained('karyawans')
                  ->cascadeOnDelete();

            // Jabatan sebelum kenaikan (snapshot saat pengajuan dibuat)
            $table->foreignId('jabatan_lama_id')
                  ->constrained('jabatans')
                  ->restrictOnDelete();

            // Jabatan baru → diisi manual oleh admin saat approve
            $table->foreignId('jabatan_baru_id')
                  ->nullable()
                  ->constrained('jabatans')
                  ->nullOnDelete();

            // Tanggal kenaikan yang diajukan (dari countdown)
            $table->date('tanggal_berlaku');

            // Diisi manual saat approve/reject → akan di-sync ke karyawans
            $table->date('tanggal_berikutnya')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending');

            $table->text('catatan')->nullable();

            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kenaikan_jabatans');
    }
};
