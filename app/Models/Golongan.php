<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Golongan extends Model
{
    protected $primaryKey = 'id_golongan';

    protected $fillable = [
        'tipe',
        'nama_golongan',
    ];

    protected $casts = [
        'tipe' => 'string', // enum: PNS | PPPK
    ];

    // ─── Relations ───────────────────────────────────────────────

    public function karyawans(): HasMany
    {
        return $this->hasMany(Karyawan::class, 'id_golongan', 'id_golongan');
    }

    public function kenaikanSebagaiLama(): HasMany
    {
        return $this->hasMany(KenaikanGolongan::class, 'golongan_lama_id', 'id_golongan');
    }

    public function kenaikanSebagaiBaru(): HasMany
    {
        return $this->hasMany(KenaikanGolongan::class, 'golongan_baru_id', 'id_golongan');
    }

    public function historiSebagaiLama(): HasMany
    {
        return $this->hasMany(HistoriGolongan::class, 'golongan_lama_id', 'id_golongan');
    }

    public function historiSebagaiBaru(): HasMany
    {
        return $this->hasMany(HistoriGolongan::class, 'golongan_baru_id', 'id_golongan');
    }
}
