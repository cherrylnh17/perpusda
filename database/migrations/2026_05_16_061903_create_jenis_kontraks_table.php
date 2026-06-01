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
        Schema::create('jenis_kontraks', function (Blueprint $table) {
            $table->id('id_jenis_kontrak');
            $table->string('nama_kontrak', 100);
            $table->integer('jam_kerja_sehari');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_kontraks');
    }
};
