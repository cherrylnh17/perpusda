<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

use App\Models\Jabatan;
use App\Models\Pendidikan;
use App\Models\JenisKontrak;
use App\Models\Golongan;
use App\Models\KenaikanGaji;
use App\Models\KenaikanJabatan;
use App\Models\NotifikasiKenaikan;

class Karyawan extends Model
{
    protected $fillable = [
        'nama_lengkap',
        'nip',
        'nik',
        'jenis_kelamin',
        'tanggal_lahir',
        'tanggal_masuk',
        'alamat',
        'agama',
        'golongan_darah',
        'jabatan_id',
        'pendidikan_id',
        'jenis_kontrak_id',
        'golongan_id',
        'status_aktif',
        'gaji',
        'tanggal_mulai_jabatan',
        'foto',

        // Kolom baru untuk countdown
        'tanggal_kenaikan_gaji_berikutnya',
        'tanggal_kenaikan_jabatan_berikutnya',
    ];

    protected $casts = [
        'tanggal_lahir'                        => 'date',
        'tanggal_masuk'                        => 'date',
        'tanggal_mulai_jabatan'                => 'date',
        'gaji'                                 => 'decimal:2',
        'tanggal_kenaikan_gaji_berikutnya'     => 'date',
        'tanggal_kenaikan_jabatan_berikutnya'  => 'date',
    ];

    // ── Relasi Master ────────────────────────────────────────────────────────

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function pendidikan()
    {
        return $this->belongsTo(Pendidikan::class);
    }

    public function jenisKontrak()
    {
        return $this->belongsTo(JenisKontrak::class);
    }

    public function golongan()
    {
        return $this->belongsTo(Golongan::class);
    }

    // ── Relasi Kenaikan ──────────────────────────────────────────────────────

    public function kenaikanGajis()
    {
        return $this->hasMany(KenaikanGaji::class);
    }

    public function kenaikanJabatans()
    {
        return $this->hasMany(KenaikanJabatan::class);
    }

    public function notifikasiKenaikans()
    {
        return $this->hasMany(NotifikasiKenaikan::class);
    }

    // ── Accessor: pengajuan kenaikan gaji yang masih pending ────────────────

    public function kenaikanGajiPending()
    {
        return $this->hasOne(KenaikanGaji::class)
                    ->where('status', 'pending')
                    ->latestOfMany();
    }

    public function kenaikanJabatanPending()
    {
        return $this->hasOne(KenaikanJabatan::class)
                    ->where('status', 'pending')
                    ->latestOfMany();
    }

    // ── Accessor: URL foto ───────────────────────────────────────────────────

    public function fotoUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->foto
                ? asset('storage/' . $this->foto)
                : null,
        );
    }

    // ── Accessor: hitung sisa hari kenaikan gaji ────────────────────────────

    public function sisaHariKenaikanGaji(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tanggal_kenaikan_gaji_berikutnya
                ? now()->startOfDay()->diffInDays(
                    $this->tanggal_kenaikan_gaji_berikutnya,
                    absolute: false
                  )
                : null,
        );
    }

    // ── Accessor: hitung sisa hari kenaikan jabatan ─────────────────────────

    public function sisaHariKenaikanJabatan(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tanggal_kenaikan_jabatan_berikutnya
                ? now()->startOfDay()->diffInDays(
                    $this->tanggal_kenaikan_jabatan_berikutnya,
                    absolute: false
                  )
                : null,
        );
    }
}
