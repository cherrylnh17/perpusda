<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Karyawan extends Model
{
    protected $primaryKey = 'id_karyawan';

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
        'foto',
        'id_jabatan',
        'id_pendidikan',
        'nama_pendidikan',
        'id_jenis_kontrak',
        'id_golongan',
        'status_aktif',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
    ];

    // ─── Relations ───────────────────────────────────────────────

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id_jabatan');
    }

    public function pendidikan(): BelongsTo
    {
        return $this->belongsTo(Pendidikan::class, 'id_pendidikan', 'id_pendidikan');
    }

    public function jenisKontrak(): BelongsTo
    {
        return $this->belongsTo(JenisKontrak::class, 'id_jenis_kontrak', 'id_jenis_kontrak');
    }

    public function golongan(): BelongsTo
    {
        return $this->belongsTo(Golongan::class, 'id_golongan', 'id_golongan');
    }

    public function kenaikanBerkalas(): HasMany
    {
        return $this->hasMany(KenaikanBerkala::class, 'id_karyawan', 'id_karyawan');
    }

    /** Berkala yang sedang aktif (scheduled atau pending, max 1) */
    public function kenaikanBerkalaAktif(): HasOne
    {
        return $this->hasOne(KenaikanBerkala::class, 'id_karyawan', 'id_karyawan')
            ->whereIn('status', ['scheduled', 'pending'])
            ->latestOfMany('tanggal_berikutnya');
    }

    public function kenaikanGolongans(): HasMany
    {
        return $this->hasMany(KenaikanGolongan::class, 'id_karyawan', 'id_karyawan');
    }

    /** Golongan yang sedang aktif (scheduled atau pending, max 1) */
    public function kenaikanGolonganAktif(): HasOne
    {
        return $this->hasOne(KenaikanGolongan::class, 'id_karyawan', 'id_karyawan')
            ->whereIn('status', ['scheduled', 'pending'])
            ->latestOfMany('tanggal_berikutnya');
    }

    public function historiGolongans(): HasMany
    {
        return $this->hasMany(HistoriGolongan::class, 'id_karyawan', 'id_karyawan')
            ->orderByDesc('tanggal_efektif');
    }
}
