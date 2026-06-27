<?php

namespace App\Exports;

use App\Models\Karyawan;
use Spatie\SimpleExcel\SimpleExcelWriter;

class KaryawanExport
{
    public function __construct(private array $filters = []) {}

    public function download(string $filename): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $query = Karyawan::with(['jabatan', 'pendidikan', 'jenisKontrak', 'golongan'])
            ->orderBy('nama_lengkap');

        if (!empty($this->filters['status'])) {
            $query->where('status_aktif', $this->filters['status']);
        }
        if (!empty($this->filters['jabatan'])) {
            $query->where('id_jabatan', $this->filters['jabatan']);
        }
        if (!empty($this->filters['kontrak'])) {
            $query->where('id_jenis_kontrak', $this->filters['kontrak']);
        }
        if (!empty($this->filters['golongan'])) {
            $query->where('id_golongan', $this->filters['golongan']);
        }

        $karyawans = $query->get();

        return response()->streamDownload(function () use ($karyawans) {
            $writer = SimpleExcelWriter::streamDownload('karyawan.xlsx');

            $writer->addHeader([
                'No',
                'NIP',
                'NIK',
                'Nama Lengkap',
                'Jenis Kelamin',
                'Tanggal Lahir',
                'Jabatan',
                'Pendidikan',
                'Golongan',
                'Jenis Kontrak',
                'Tgl Masuk',
                'Agama',
                'Golongan Darah',
                'Status',
                'Alamat',
            ]);

            $karyawans->each(function ($k, $i) use ($writer) {
                $writer->addRow([
                    $i + 1,
                    $k->nip,
                    $k->nik,
                    $k->nama_lengkap,
                    $k->jenis_kelamin ?? '-',
                    $k->tanggal_lahir?->format('d/m/Y') ?? '-',
                    $k->jabatan?->nama_jabatan ?? '-',
                    $k->pendidikan?->jenjang ?? ($k->nama_pendidikan ?? '-'),
                    $k->golongan?->nama_golongan ?? '-',
                    $k->jenisKontrak?->nama_kontrak ?? '-',
                    $k->tanggal_masuk?->format('d/m/Y'),
                    $k->agama ?? '-',
                    $k->golongan_darah ?? '-',
                    $k->status_aktif,
                    $k->alamat ?? '-',
                ]);
            });

            $writer->close();
        }, $filename);
    }
}
