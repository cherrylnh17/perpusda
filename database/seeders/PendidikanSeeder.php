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
        $jenjangList = ['SMA', 'SMP', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3'];

        $total = 0;
        foreach ($jenjangList as $jenjang) {
            Pendidikan::firstOrCreate(['jenjang' => $jenjang]);
            $total++;
        }

        $this->command->info("PendidikanSeeder: {$total} data pendidikan berhasil di-seed.");
    }
}
