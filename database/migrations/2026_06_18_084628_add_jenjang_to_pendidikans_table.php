<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendidikans', function (Blueprint $table) {
            $table->enum('jenjang', ['S1', 'S2', 'S3', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'D4'])
                  ->after('id_pendidikan');
        });
    }

    public function down(): void
    {
        Schema::table('pendidikans', function (Blueprint $table) {
            $table->dropColumn('jenjang');
        });
    }
};
