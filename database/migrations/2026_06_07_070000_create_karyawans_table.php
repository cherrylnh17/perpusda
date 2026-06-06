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
            $table->id();

            $table->string('nama_lengkap', 255);
            $table->string('nip', 50)->unique();
            $table->string('nik', 20)->unique();
            $table->date('tanggal_masuk');
            $table->text('alamat')->nullable();
            $table->string('agama', 50)->nullable();
            $table->string('golongan_darah', 2)->nullable();

            $table->string('foto')->nullable();

            $table->foreignId('jabatan_id')
                ->nullable()
                ->constrained('jabatans')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('pendidikan_id')
                ->nullable()
                ->constrained('pendidikans')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('jenis_kontrak_id')
                ->nullable()
                ->constrained('jenis_kontraks')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('golongan_id')
                ->nullable()
                ->constrained('golongans')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->enum('status_aktif', [
                'Aktif',
                'Cuti',
                'Pensiun',
                'Resign'
            ])->default('Aktif');

            $table->decimal('gaji', 15, 2)->default(0);

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
