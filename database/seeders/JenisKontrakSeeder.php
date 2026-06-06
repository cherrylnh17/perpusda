<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisKontrak;

class JenisKontrakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisKontraks = [
            [
                'nama_kontrak' => 'PNS',
                'jam_kerja_sehari' => 8,
            ],
            [
                'nama_kontrak' => 'P3K Penuh Waktu',
                'jam_kerja_sehari' => 8,
            ],
            [
                'nama_kontrak' => 'P3K Paruh Waktu',
                'jam_kerja_sehari' => 8,
            ],
            [
                'nama_kontrak' => 'Outsourcing',
                'jam_kerja_sehari' => 8,
            ],
        
        ];

        $total = 0;

        foreach ($jenisKontraks as $jenisKontrak) {
            JenisKontrak::firstOrCreate(
                ['nama_kontrak' => $jenisKontrak['nama_kontrak']],
                ['jam_kerja_sehari' => $jenisKontrak['jam_kerja_sehari']]
            );

            $total++;
        }

        $this->command->info("JenisKontrakSeeder: {$total} data berhasil di-seed.");
    }
}
