<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * Pengajuan Kenaikan Berkala
         * ─────────────────────────────────────────────────────────────────
         * Kenaikan gaji rutin tiap 2 tahun. Nominal gaji tidak disimpan
         * di sini (privasi). Tabel ini hanya mencatat bahwa karyawan
         * sudah memenuhi syarat dan pengajuan telah disetujui.
         *
         * Setelah diterima:
         *   - tanggal_efektif disalin ke karyawans.tanggal_berkala_terakhir
         *   - tanggal_berkala_berikutnya diupdate (+2 tahun)
         */
        Schema::create('pengajuan_kenaikan_berkalas', function (Blueprint $table) {
            $table->id('id_pengajuan_berkala');

            $table->foreignId('id_karyawan')
                ->constrained('karyawans', 'id_karyawan')
                ->cascadeOnDelete();

            // Periode yang diajukan
            $table->date('tanggal_efektif');

            $table->enum('status', ['pending', 'diterima'])->default('pending');

            $table->text('catatan')->nullable();

            $table->foreignId('diproses_oleh')->nullable()
                ->constrained('users', 'id')
                ->nullOnDelete();

            $table->timestamp('diproses_pada')->nullable();

            $table->timestamps();

            // 1 karyawan hanya boleh punya 1 pengajuan berkala pending
            $table->unique(['id_karyawan', 'status'], 'unique_berkala_pending');
        });

        /**
         * Pengajuan Kenaikan Golongan
         * ─────────────────────────────────────────────────────────────────
         * Kenaikan grade/level internal (misal I/A → I/B).
         *
         * Setelah diterima:
         *   - karyawans.id_golongan diupdate ke golongan_baru_id
         *   - record disalin ke histori_golongans
         */
        Schema::create('pengajuan_kenaikan_golongans', function (Blueprint $table) {
            $table->id('id_pengajuan_golongan');

            $table->foreignId('id_karyawan')
                ->constrained('karyawans', 'id_karyawan')
                ->cascadeOnDelete();

            // Snapshot golongan sebelum naik
            $table->foreignId('golongan_lama_id')->nullable()
                ->constrained('golongans', 'id_golongan')
                ->nullOnDelete();

            // Golongan yang diusulkan
            $table->foreignId('golongan_baru_id')->nullable()
                ->constrained('golongans', 'id_golongan')
                ->nullOnDelete();

            $table->date('tanggal_efektif');

            $table->enum('status', ['pending', 'diterima'])->default('pending');

            $table->text('catatan')->nullable();

            $table->foreignId('diproses_oleh')->nullable()
                ->constrained('users', 'id')
                ->nullOnDelete();

            $table->timestamp('diproses_pada')->nullable();

            $table->timestamps();

            // 1 karyawan hanya boleh punya 1 pengajuan golongan pending
            $table->unique(['id_karyawan', 'status'], 'unique_golongan_pending');
        });

        /**
         * Histori Golongan
         * ─────────────────────────────────────────────────────────────────
         * Setiap kali pengajuan kenaikan golongan diterima, satu baris
         * dicatat di sini sebagai riwayat permanen.
         */
        Schema::create('histori_golongans', function (Blueprint $table) {
            $table->id('id_histori');

            $table->foreignId('id_karyawan')
                ->constrained('karyawans', 'id_karyawan')
                ->cascadeOnDelete();

            $table->foreignId('golongan_lama_id')->nullable()
                ->constrained('golongans', 'id_golongan')
                ->nullOnDelete();

            $table->foreignId('golongan_baru_id')
                ->constrained('golongans', 'id_golongan')
                ->cascadeOnDelete();

            $table->date('tanggal_efektif');

            // Referensi ke pengajuan asalnya (opsional, untuk audit)
            $table->foreignId('id_pengajuan_golongan')->nullable()
                ->constrained('pengajuan_kenaikan_golongans', 'id_pengajuan_golongan')
                ->nullOnDelete();

            $table->foreignId('dicatat_oleh')->nullable()
                ->constrained('users', 'id')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['id_karyawan', 'tanggal_efektif']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('histori_golongans');
        Schema::dropIfExists('pengajuan_kenaikan_golongans');
        Schema::dropIfExists('pengajuan_kenaikan_berkalas');
    }
};
