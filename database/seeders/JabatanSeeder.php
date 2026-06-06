<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jabatan;

class JabatanSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $jabatans = [
            //  Pimpinan kontrak
            'Kepala Dinas',


            'Sekretaris',


            'JF Pustakawan Ahli Madya',
            'JF Arsipasi Ahli Madya',


            'JF Perencana Ahli Pertama',
            'JF Pranata Komputer Ahli Pertama',
            'JF Pranata Komputer Pelaksana Lanjutan/ Mahir',
            'JF Pranata Komputer Pelaksana/ Terampil',
            'Penelaah Teknis Kebijakan',
            'Pengolah Data dan Informasi',
            'Pengadministrasi Perkebunan',


            'JF Arsiparis Pelaksana/ Terampil',
            'Penelaah Teknis Kebijakan',
            'Pengelola Layanan Operasional',
            'Pengolah Data dan Informasi',
            'Pengadministrasi Perkantoran',
            'Pengelola Umum Operasional',


            'JF Perencana Ahli Muda',


            'JF Arsiparis Ahli Muda',
            'JF Arsiparis Ahli Pertama',
            'JF Arsiparis Penyelia',
            'JF Arsiparis Pelaksana Lanjutan/ Mahir',
            'JF Arsiparis Pelaksana/ Terampil',
            'Pengadministrasi Perkantoran',


            'JF Pustakawan Ahli Muda ',
            'JF Pustakawan Ahli Pertama',
            'JF Asisten Perpustakaan Penyelia',
            'JF Asisten Perpustakaan Pelaksana Lanjutan/ Mahir ',
            'JF Asisten Perpustakaan Pelaksana/ Terampil',
            'Penelaah Teknis Kebijakan',
            'Pengolah Data dan Informasi ',
            'Pengadministrasi Perkantoran',

        ];

        foreach ($jabatans as $nama) {
            Jabatan::firstOrCreate(['nama_jabatan' => $nama]);
        }

        $this->command->info('JabatanSeeder: ' . count($jabatans) . ' jabatan berhasil di-seed.');
    }
}
