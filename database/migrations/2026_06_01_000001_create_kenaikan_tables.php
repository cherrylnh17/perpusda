<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * Kenaikan Berkala
         * ─────────────────────────────────────────────────────────────────
         * Kenaikan gaji rutin tiap 2 tahun.
         *
         * Status flow:
         *   scheduled → (cron harian, now >= tanggal_berikutnya) → pending
         *   pending   → diterima → sistem insert row baru (scheduled, +2 tahun)
         *   pending   → stop     → tidak ada row baru (sudah maksimal)
         */
        Schema::create('kenaikan_berkalas', function (Blueprint $table) {
            $table->id('id_berkala');

            $table->foreignId('id_karyawan')
                ->constrained('karyawans', 'id_karyawan')
                ->cascadeOnDelete();

            $table->date('tanggal_berikutnya');

            $table->enum('status', ['scheduled', 'pending', 'diterima', 'stop'])
                ->default('scheduled');

            $table->text('catatan')->nullable();

            $table->foreignId('diproses_oleh')->nullable()
                ->constrained('users', 'id')
                ->nullOnDelete();

            $table->timestamp('diproses_pada')->nullable();

            $table->timestamps();

            $table->index(['id_karyawan', 'status']);
            $table->index('tanggal_berikutnya');
        });

        /**
         * Kenaikan Golongan
         * ─────────────────────────────────────────────────────────────────
         * Kenaikan grade/level internal (misal I/A → I/B).
         *
         * Status flow:
         *   scheduled → (cron harian, now >= tanggal_berikutnya) → pending
         *   pending   → diterima → update id_golongan karyawan
         *                        → insert ke histori_golongans
         *                        → admin set tanggal_berikutnya baru (insert row scheduled baru)
         *                           atau dibiarkan null (sudah golongan teratas)
         *   pending   → stop     → tidak ada row baru
         */
        Schema::create('kenaikan_golongans', function (Blueprint $table) {
            $table->id('id_golongan_kenaikan');

            $table->foreignId('id_karyawan')
                ->constrained('karyawans', 'id_karyawan')
                ->cascadeOnDelete();

            // Snapshot golongan sebelum naik (diisi saat pending/diterima)
            $table->foreignId('golongan_lama_id')->nullable()
                ->constrained('golongans', 'id_golongan')
                ->nullOnDelete();

            // Golongan yang diusulkan (diisi saat approve)
            $table->foreignId('golongan_baru_id')->nullable()
                ->constrained('golongans', 'id_golongan')
                ->nullOnDelete();

            $table->date('tanggal_berikutnya');

            $table->enum('status', ['scheduled', 'pending', 'diterima', 'stop'])
                ->default('scheduled');

            $table->text('catatan')->nullable();

            $table->foreignId('diproses_oleh')->nullable()
                ->constrained('users', 'id')
                ->nullOnDelete();

            $table->timestamp('diproses_pada')->nullable();

            $table->timestamps();

            $table->index(['id_karyawan', 'status']);
            $table->index('tanggal_berikutnya');
        });

        /**
         * Histori Golongan
         * ─────────────────────────────────────────────────────────────────
         * Setiap kali kenaikan golongan diterima, satu baris dicatat
         * di sini sebagai riwayat permanen.
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

            // Referensi ke pengajuan asal (untuk audit)
            $table->foreignId('id_golongan_kenaikan')->nullable()
                ->constrained('kenaikan_golongans', 'id_golongan_kenaikan')
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
        Schema::dropIfExists('kenaikan_golongans');
        Schema::dropIfExists('kenaikan_berkalas');
    }
};
