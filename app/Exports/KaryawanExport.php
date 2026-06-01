<?php

namespace App\Exports;

use App\Models\Karyawan;
use Spatie\SimpleExcel\SimpleExcelWriter;

class KaryawanExport
{
    public function __construct(private array $filters = []) {}

    public function download(string $filename): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $query = Karyawan::with(['jabatan', 'pendidikan', 'jenisKontrak'])
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
                'Jenis Kontrak',
                'Tgl Masuk',
                'Tgl Mulai Jabatan',
                'Agama',
                'Golongan Darah',
                'Status',
                'Gaji',
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
                    $k->pendidikan?->nama_pendidikan ?? '-',
                    $k->jenisKontrak?->nama_kontrak ?? '-',
                    $k->tanggal_masuk?->format('d/m/Y'),
                    $k->tanggal_mulai_jabatan?->format('d/m/Y'),
                    $k->agama ?? '-',
                    $k->golongan_darah ?? '-',
                    $k->status_aktif,
                    number_format($k->gaji, 0, ',', '.'),
                    $k->alamat ?? '-',
                ]);
            });

            $writer->close();
        }, $filename);
    }
}
