<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Golongan;

class GolonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $golongans = [

            // PNS
            [
                'tipe' => 'PNS',
                'nama_golongan' => 'Pengatur Muda Tingkat I',
            ],
            [
                'tipe' => 'PNS',
                'nama_golongan' => 'Pengatur',
            ],
            [
                'tipe' => 'PNS',
                'nama_golongan' => 'Pengatur Tingkat I',
            ],

            [
                'tipe' => 'PNS',
                'nama_golongan' => 'Penata Muda',
            ],
            [
                'tipe' => 'PNS',
                'nama_golongan' => 'Penata Muda Tingkat I',
            ],
            [
                'tipe' => 'PNS',
                'nama_golongan' => 'Penata',
            ],
            [
                'tipe' => 'PNS',
                'nama_golongan' => 'Penata Tingkat I',
            ],

            [
                'tipe' => 'PNS',
                'nama_golongan' => 'Pembina',
            ],
            [
                'tipe' => 'PNS',
                'nama_golongan' => 'Pembina Tingkat I',
            ],
            [
                'tipe' => 'PNS',
                'nama_golongan' => 'Pembina Utama Muda',
            ],
            [
                'tipe' => 'PNS',
                'nama_golongan' => 'Pembina Utama Madya',
            ],
            [
                'tipe' => 'PNS',
                'nama_golongan' => 'Pembina Utama',
            ],

            // PPPK
            [
                'tipe' => 'PPPK',
                'nama_golongan' => 'Lulusan SD',
            ],
            [
                'tipe' => 'PPPK',
                'nama_golongan' => 'SLTA/SMA atau Diploma I (D1)',
            ],
            [
                'tipe' => 'PPPK',
                'nama_golongan' => 'Diploma III (D3)',
            ],
            [
                'tipe' => 'PPPK',
                'nama_golongan' => 'Diploma IV (D4) atau Sarjana (S1)',
            ],
        ];

        $total = 0;

        foreach ($golongans as $golongan) {
            Golongan::firstOrCreate([
                'tipe' => $golongan['tipe'],
                'nama_golongan' => $golongan['nama_golongan'],
            ]);

            $total++;
        }

        $this->command->info("GolonganSeeder: {$total} data berhasil di-seed.");
    }
}
