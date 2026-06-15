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
        'id_jenis_kontrak',
        'id_golongan',
        'status_aktif',
        'tanggal_berkala_terakhir',
        'tanggal_berkala_berikutnya',
        'tanggal_mulai_golongan',
    ];

    protected $casts = [
        'tanggal_lahir'              => 'date',
        'tanggal_masuk'              => 'date',
        'tanggal_mulai_golongan'      => 'date',
        'tanggal_berkala_terakhir'   => 'date',
        'tanggal_berkala_berikutnya' => 'date',
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

    public function pengajuanBerkalas(): HasMany
    {
        return $this->hasMany(PengajuanKenaikanBerkala::class, 'id_karyawan', 'id_karyawan');
    }

    /** Pengajuan berkala yang sedang pending (max 1) */
    public function pengajuanBerkalaPending(): HasOne
    {
        return $this->hasOne(PengajuanKenaikanBerkala::class, 'id_karyawan', 'id_karyawan')
            ->where('status', 'pending');
    }

    public function pengajuanGolongans(): HasMany
    {
        return $this->hasMany(PengajuanKenaikanGolongan::class, 'id_karyawan', 'id_karyawan');
    }

    /** Pengajuan golongan yang sedang pending (max 1) */
    public function pengajuanGolonganPending(): HasOne
    {
        return $this->hasOne(PengajuanKenaikanGolongan::class, 'id_karyawan', 'id_karyawan')
            ->where('status', 'pending');
    }

    public function historiGolongans(): HasMany
    {
        return $this->hasMany(HistoriGolongan::class, 'id_karyawan', 'id_karyawan')
            ->orderByDesc('tanggal_efektif');
    }

    // ─── Helpers ─────────────────────────────────────────────────

    /** Apakah karyawan sudah memenuhi syarat kenaikan berkala */
    public function sudahJatuhTempoBerkala(): bool
    {
        if (! $this->tanggal_berkala_berikutnya) {
            return false;
        }

        return now()->gte($this->tanggal_berkala_berikutnya);
    }
}
