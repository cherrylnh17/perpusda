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
        Schema::create('golongans', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe', ['PNS', 'PPPK']);
            $table->string('nama_golongan', 100);
            $table->timestamps();

            $table->unique(['tipe', 'nama_golongan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('golongans');
    }
};
