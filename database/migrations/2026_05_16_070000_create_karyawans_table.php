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
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id('id_karyawan');
            $table->string('nama_lengkap', 255);
            $table->string('nip', 50)->unique();
            $table->string('nik', 20)->unique();
            $table->date('tanggal_masuk');
            $table->text('alamat')->nullable();
            $table->string('agama', 50)->nullable();
            $table->string('golongan_darah', 2)->nullable();

            // Kolom foto
            $table->string('foto', 255)->nullable();

            $table->foreignId('id_jabatan')->nullable()
                ->constrained('jabatans', 'id_jabatan')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('id_pendidikan')->nullable()
                ->constrained('pendidikans', 'id_pendidikan')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('id_jenis_kontrak')->nullable()
                ->constrained('jenis_kontraks', 'id_jenis_kontrak')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->enum('status_aktif', ['Aktif', 'Cuti', 'Pensiun', 'Resign'])
                ->default('Aktif');

            $table->decimal('gaji', 15, 2)->default(0.00);
            $table->date('tanggal_mulai_jabatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
