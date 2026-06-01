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
            'Sekretaris Dinas',

            //  Subbagian kontrak
            'Subbagian Perencanaan, Evaluasi, Pelaporan dan Keuangan',
            'Subbagian Umum dan Kepegawaian',

            //  Jabatan Fungsional Kearsipan
            'Kelompok Jabatan Fungsional',
            'Koordinator Kearsipan',
            'Arsiparis Ahli',
            'Arsiparis Terampil',
            'Pengelola Arsip',
            'Petugas Digitalisasi Arsip',
            'Petugas Preservasi Arsip',
            'Petugas Akuisisi Arsip',
            'Pengadministrasi Kearsipan',
            'Kelompok Jabatan Fungsional Bidang Kearsipan',

            //  Jabatan Fungsional Perpustakaan
            'Koordinator Perpustakaan',
            'Pustakawan Ahli',
            'Pustakawan Terampil',
            'Pengelola Koleksi Buku',
            'Petugas Layanan Perpustakaan',
            'Petugas Sirkulasi Buku',
            'Petugas Perpustakaan Keliling',
            'Operator Sistem Perpustakaan',
            'Pengadministrasi Perpustakaan',
            'Kelompok Jabatan Fungsional Bidang Perpustakaan',

            //  Jabatan Fungsional Umum
            'Pustakawan',
            'Arsiparis',
            'Pranata Komputer',
            'Analis SDM Aparatur',
            'Perencana',
            'Pengelola Keuangan',
            'Bendahara',
            'Verifikator Keuangan',
            'Pengadministrasi Umum',
            'Pengelola Barang dan Aset',

            //  Staf & Pendukung
            'Operator Komputer',
            'Staff Tata Usaha',
            'Pengemudi',
            'Satpam',
            'Petugas Kebersihan',
        ];

        foreach ($jabatans as $nama) {
            Jabatan::firstOrCreate(['nama_jabatan' => $nama]);
        }

        $this->command->info('JabatanSeeder: ' . count($jabatans) . ' jabatan berhasil di-seed.');
    }
}
