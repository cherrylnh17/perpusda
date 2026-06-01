<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

use App\Models\Jabatan;
use App\Models\Pendidikan;
use App\Models\JenisKontrak;

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
        'id_jabatan',
        'id_pendidikan',
        'id_jenis_kontrak',
        'status_aktif',
        'gaji',
        'tanggal_mulai_jabatan',
        'foto',
    ];

    protected $casts = [
        'tanggal_lahir'        => 'date',
        'tanggal_masuk'        => 'date',
        'tanggal_mulai_jabatan'=> 'date',
        'gaji'                 => 'decimal:2',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────────

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id_jabatan');
    }

    public function pendidikan()
    {
        return $this->belongsTo(Pendidikan::class, 'id_pendidikan', 'id_pendidikan');
    }

    public function jenisKontrak()
    {
        return $this->belongsTo(JenisKontrak::class, 'id_jenis_kontrak', 'id_jenis_kontrak');
    }

    // ── Accessor: URL foto (fallback ke avatar inisial) ─────────────────────

    public function fotoUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->foto
                ? asset('storage/' . $this->foto)
                : null,
        );
    }
}
