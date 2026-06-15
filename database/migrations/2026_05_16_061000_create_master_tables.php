<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendidikans', function (Blueprint $table) {
            $table->id('id_pendidikan');
            $table->string('nama_pendidikan', 100);
            $table->timestamps();
        });

        Schema::create('jabatans', function (Blueprint $table) {
            $table->id('id_jabatan');
            $table->string('nama_jabatan', 150);
            $table->timestamps();
        });

        Schema::create('jenis_kontraks', function (Blueprint $table) {
            $table->id('id_jenis_kontrak');
            $table->string('nama_kontrak', 100);
            $table->integer('jam_kerja_sehari');
            $table->timestamps();
        });

        Schema::create('golongans', function (Blueprint $table) {
            $table->id('id_golongan');
            $table->enum('tipe', ['PNS', 'PPPK']);
            $table->string('nama_golongan', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('golongans');
        Schema::dropIfExists('jenis_kontraks');
        Schema::dropIfExists('jabatans');
        Schema::dropIfExists('pendidikans');
    }
};
