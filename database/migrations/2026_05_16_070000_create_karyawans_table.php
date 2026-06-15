<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id('id_karyawan');
            $table->string('nama_lengkap', 255);
            $table->string('nip', 50)->unique();
            $table->string('nik', 20)->unique();

            // Demografi
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->date('tanggal_lahir')->nullable();

            $table->date('tanggal_masuk');
            $table->text('alamat')->nullable();
            $table->string('agama', 50)->nullable();
            $table->string('golongan_darah', 2)->nullable();
            $table->string('foto', 255)->nullable();

            // Relasi master
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

            // Golongan aktif saat ini
            $table->foreignId('id_golongan')->nullable()
                ->constrained('golongans', 'id_golongan')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->enum('status_aktif', ['Aktif',  'Pensiun'])
                ->default('Aktif');

            // Jadwal kenaikan berkala (tiap 2 tahun)
            // Tanggal berkala terakhir disetujui → baseline periode berikutnya
            $table->date('tanggal_berkala_terakhir')->nullable();
            $table->date('tanggal_berkala_berikutnya')->nullable();

            // Jabatan
            $table->date('tanggal_mulai_golongan');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
