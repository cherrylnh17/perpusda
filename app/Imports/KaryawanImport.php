<?php

namespace App\Imports;

use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Pendidikan;
use App\Models\JenisKontrak;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;

class KaryawanImport
{
    public array $errors   = [];
    public int   $imported = 0;

    // Peta header kolom template → key internal
    // Kunci = nama kolom di file Excel (lowercase, trimmed)
    // Value = nama field yang akan dipakai di kode
    private const COLUMN_MAP = [
        'nip'                => 'nip',
        'nik'                => 'nik',
        'nama lengkap'       => 'nama_lengkap',
        'nama_lengkap'       => 'nama_lengkap',
        'jenis kelamin'      => 'jenis_kelamin',
        'jenis_kelamin'      => 'jenis_kelamin',
        'tanggal lahir'      => 'tanggal_lahir',
        'tanggal_lahir'      => 'tanggal_lahir',
        'tgl lahir'          => 'tanggal_lahir',
        'tgl_lahir'          => 'tanggal_lahir',
        'jabatan'            => 'jabatan',
        'pendidikan'         => 'pendidikan',
        'jenis kontrak'      => 'jenis_kontrak',
        'jenis_kontrak'      => 'jenis_kontrak',
        'tgl masuk'          => 'tgl_masuk',
        'tgl_masuk'          => 'tgl_masuk',
        'tanggal masuk'      => 'tgl_masuk',
        'tanggal_masuk'      => 'tgl_masuk',
        'tgl mulai jabatan'  => 'tgl_mulai_jabatan',
        'tgl_mulai_jabatan'  => 'tgl_mulai_jabatan',
        'tanggal mulai jabatan' => 'tgl_mulai_jabatan',
        'agama'              => 'agama',
        'golongan darah'     => 'golongan_darah',
        'golongan_darah'     => 'golongan_darah',
        'status'             => 'status',
        'status aktif'       => 'status',
        'status_aktif'       => 'status',
        'gaji'               => 'gaji',
        'alamat'             => 'alamat',
    ];

    public function import(UploadedFile $file): void
    {
        SimpleExcelReader::create($file->getPathname(), 'xlsx')
            ->getRows()
            ->each(function (array $rawRow) {
                // Normalisasi key: lowercase + trim
                $row = $this->normalizeRow($rawRow);

                // Skip baris kosong / header duplikat
                if (empty($row['nama_lengkap']) || empty($row['nip'])) {
                    return;
                }

                try {
                    // ── Lookup relasi ──────────────────────────────────
                    $jabatan    = isset($row['jabatan'])
                        ? Jabatan::whereRaw('LOWER(nama_jabatan) = ?', [strtolower($row['jabatan'])])->first()
                        : null;

                    $pendidikan = isset($row['pendidikan'])
                        ? Pendidikan::whereRaw('LOWER(nama_pendidikan) = ?', [strtolower($row['pendidikan'])])->first()
                        : null;

                    $kontrak    = isset($row['jenis_kontrak'])
                        ? JenisKontrak::whereRaw('LOWER(nama_kontrak) = ?', [strtolower($row['jenis_kontrak'])])->first()
                        : null;

                    // ── Parse tanggal ──────────────────────────────────
                    $tanggalMasuk        = $this->parseDate($row['tgl_masuk'] ?? null);
                    $tanggalMulaiJabatan = $this->parseDate($row['tgl_mulai_jabatan'] ?? null);
                    $tanggalLahir        = $this->parseDate($row['tanggal_lahir'] ?? null);

                    // ── Normalisasi jenis kelamin ──────────────────────
                    $jenisKelamin = null;
                    if (!empty($row['jenis_kelamin'])) {
                        $jk = strtolower(trim($row['jenis_kelamin']));
                        if (in_array($jk, ['laki-laki', 'laki', 'l', 'pria', 'm', 'male'])) {
                            $jenisKelamin = 'Laki-laki';
                        } elseif (in_array($jk, ['perempuan', 'p', 'wanita', 'f', 'female'])) {
                            $jenisKelamin = 'Perempuan';
                        }
                    }

                    // ── Normalisasi gaji ───────────────────────────────
                    $gaji = 0;
                    if (!empty($row['gaji'])) {
                        $gaji = (float) str_replace(['.', ',', ' ', 'Rp'], ['', '.', '', ''], (string) $row['gaji']);
                    }

                    // ── Normalisasi status ─────────────────────────────
                    $status = 'Aktif';
                    if (!empty($row['status'])) {
                        $statusMap = [
                            'aktif'   => 'Aktif',
                            'cuti'    => 'Cuti',
                            'pensiun' => 'Pensiun',
                            'resign'  => 'Resign',
                        ];
                        $status = $statusMap[strtolower(trim($row['status']))] ?? 'Aktif';
                    }

                    // ── Simpan / update ────────────────────────────────
                    Karyawan::updateOrCreate(
                        ['nip' => (string) trim($row['nip'])],
                        array_filter([
                            'nama_lengkap'          => trim($row['nama_lengkap']),
                            'nik'                   => (string) trim($row['nik'] ?? ''),
                            'jenis_kelamin'         => $jenisKelamin,
                            'tanggal_lahir'         => $tanggalLahir,
                            'tanggal_masuk'         => $tanggalMasuk,
                            'tanggal_mulai_jabatan' => $tanggalMulaiJabatan,
                            'alamat'                => $row['alamat'] ?? null,
                            'agama'                 => $row['agama'] ?? null,
                            'golongan_darah'        => $row['golongan_darah'] ?? null,
                            'id_jabatan'            => $jabatan?->id_jabatan,
                            'id_pendidikan'         => $pendidikan?->id_pendidikan,
                            'id_jenis_kontrak'      => $kontrak?->id_jenis_kontrak,
                            'status_aktif'          => $status,
                            'gaji'                  => $gaji,
                        ], fn ($v) => $v !== null)
                    );

                    $this->imported++;

                } catch (\Throwable $e) {
                    $nip = $row['nip'] ?? '?';
                    $this->errors[] = "Baris NIP {$nip}: " . $e->getMessage();
                }
            });
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Normalisasi semua key dari Excel menjadi key internal
     * berdasarkan COLUMN_MAP.
     */
    private function normalizeRow(array $raw): array
    {
        $normalized = [];
        foreach ($raw as $key => $value) {
            $cleanKey = strtolower(trim((string) $key));
            $mappedKey = self::COLUMN_MAP[$cleanKey] ?? $cleanKey;
            $normalized[$mappedKey] = $value;
        }
        return $normalized;
    }

    /**
     * Parse berbagai format tanggal: d/m/Y, Y-m-d, d-m-Y, Excel serial, dll.
     */
    private function parseDate(mixed $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        // Excel serial number (angka numerik)
        if (is_numeric($value)) {
            try {
                return Carbon::createFromTimestamp(($value - 25569) * 86400)->format('Y-m-d');
            } catch (\Throwable) {
                return null;
            }
        }

        $formats = ['d/m/Y', 'Y-m-d', 'd-m-Y', 'd M Y', 'd F Y', 'm/d/Y', 'Y/m/d'];
        foreach ($formats as $fmt) {
            try {
                return Carbon::createFromFormat($fmt, trim((string) $value))->format('Y-m-d');
            } catch (\Throwable) {
                continue;
            }
        }

        // Fallback ke Carbon::parse
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }
}
