<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pendidikan;

class PendidikanSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $pendidikans = [
            // - Pendidikan -
            'SD',
            'SMP',
            'SMA',
            'SMK',
            'S1',
            'S2',
        ];

        $total = 0;
        foreach ($pendidikans as $nama) {
            Pendidikan::firstOrCreate(['nama_pendidikan' => $nama]);
            $total++;
        }

        $this->command->info("PendidikanSeeder: {$total} data pendidikan berhasil di-seed.");
    }
}
