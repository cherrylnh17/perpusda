<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Pendidikan;
use App\Models\JenisKontrak;
use App\Models\Golongan;

class KaryawanSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Pastikan data master sudah ada
        $jabatan   = Jabatan::first();
        $pendidikan = Pendidikan::first();
        $kontrak    = JenisKontrak::first();
        $golongan   = Golongan::first();

        if (! $jabatan || ! $pendidikan || ! $kontrak || ! $golongan) {
            $this->command->warn('KaryawanSeeder: Data master (Jabatan/Pendidikan/Kontrak/Golongan) belum ada. Seeder dilewati.');
            return;
        }

        $karyawans = [
            [
                'nama_lengkap'     => 'Ahmad Fauzi',
                'nip'              => 'NIP-2021-001',
                'nik'              => '3201234567890001',
                'jenis_kelamin'    => 'Laki-laki',
                'tanggal_lahir'    => '1990-05-15',
                'tanggal_masuk'    => '2021-03-01',
                'alamat'           => 'Jl. Merdeka No. 10, Bandung',
                'agama'            => 'Islam',
                'golongan_darah'   => 'A',
                'id_jabatan'       => $jabatan->id_jabatan,
                'id_pendidikan'    => $pendidikan->id_pendidikan,
                'nama_pendidikan'  => 'Universitas Padjadjaran',
                'id_jenis_kontrak' => $kontrak->id_jenis_kontrak,
                'id_golongan'      => $golongan->id_golongan,
                'status_aktif'     => 'Aktif',
            ],
            [
                'nama_lengkap'     => 'Siti Nurhaliza',
                'nip'              => 'NIP-2020-002',
                'nik'              => '3201234567890002',
                'jenis_kelamin'    => 'Perempuan',
                'tanggal_lahir'    => '1992-08-20',
                'tanggal_masuk'    => '2020-06-15',
                'alamat'           => 'Jl. Cendana No. 5, Bandung',
                'agama'            => 'Islam',
                'golongan_darah'   => 'B',
                'id_jabatan'       => $jabatan->id_jabatan,
                'id_pendidikan'    => $pendidikan->id_pendidikan,
                'nama_pendidikan'  => 'Universitas Indonesia',
                'id_jenis_kontrak' => $kontrak->id_jenis_kontrak,
                'id_golongan'      => $golongan->id_golongan,
                'status_aktif'     => 'Aktif',
            ],
            [
                'nama_lengkap'     => 'Budi Santoso',
                'nip'              => 'NIP-2019-003',
                'nik'              => '3201234567890003',
                'jenis_kelamin'    => 'Laki-laki',
                'tanggal_lahir'    => '1988-11-02',
                'tanggal_masuk'    => '2019-01-10',
                'alamat'           => 'Jl. Dago No. 22, Bandung',
                'agama'            => 'Kristen',
                'golongan_darah'   => 'O',
                'id_jabatan'       => $jabatan->id_jabatan,
                'id_pendidikan'    => $pendidikan->id_pendidikan,
                'nama_pendidikan'  => 'Institut Teknologi Bandung',
                'id_jenis_kontrak' => $kontrak->id_jenis_kontrak,
                'id_golongan'      => $golongan->id_golongan,
                'status_aktif'     => 'Aktif',
            ],
            [
                'nama_lengkap'     => 'Dewi Kartika',
                'nip'              => 'NIP-2022-004',
                'nik'              => '3201234567890004',
                'jenis_kelamin'    => 'Perempuan',
                'tanggal_lahir'    => '1995-03-25',
                'tanggal_masuk'    => '2022-07-01',
                'alamat'           => 'Jl. Buah Batu No. 15, Bandung',
                'agama'            => 'Islam',
                'golongan_darah'   => 'AB',
                'id_jabatan'       => $jabatan->id_jabatan,
                'id_pendidikan'    => $pendidikan->id_pendidikan,
                'nama_pendidikan'  => 'Universitas Telkom',
                'id_jenis_kontrak' => $kontrak->id_jenis_kontrak,
                'id_golongan'      => $golongan->id_golongan,
                'status_aktif'     => 'Aktif',
            ],
            [
                'nama_lengkap'     => 'Rizky Pratama',
                'nip'              => 'NIP-2023-005',
                'nik'              => '3201234567890005',
                'jenis_kelamin'    => 'Laki-laki',
                'tanggal_lahir'    => '1997-07-10',
                'tanggal_masuk'    => '2023-02-15',
                'alamat'           => 'Jl. Setiabudhi No. 30, Bandung',
                'agama'            => 'Islam',
                'golongan_darah'   => 'A',
                'id_jabatan'       => $jabatan->id_jabatan,
                'id_pendidikan'    => $pendidikan->id_pendidikan,
                'nama_pendidikan'  => 'Universitas Pendidikan Indonesia',
                'id_jenis_kontrak' => $kontrak->id_jenis_kontrak,
                'id_golongan'      => $golongan->id_golongan,
                'status_aktif'     => 'Aktif',
            ],
        ];

        $total = 0;
        foreach ($karyawans as $data) {
            Karyawan::firstOrCreate(
                ['nip' => $data['nip']],
                $data,
            );
            $total++;
        }

        $this->command->info("KaryawanSeeder: {$total} data karyawan berhasil di-seed.");
    }
}
