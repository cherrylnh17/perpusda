<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisKontrak extends Model
{
    protected $primaryKey = 'id_jenis_kontrak';

    protected $fillable = [
        'nama_kontrak',
        'jam_kerja_sehari',
    ];

    protected $casts = [
        'jam_kerja_sehari' => 'integer',
    ];

    // ─── Relations ───────────────────────────────────────────────

    public function karyawans(): HasMany
    {
        return $this->hasMany(Karyawan::class, 'id_jenis_kontrak', 'id_jenis_kontrak');
    }
}
