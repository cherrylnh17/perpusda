<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pendidikan extends Model
{
    protected $primaryKey = 'id_pendidikan';

    protected $fillable = [
        'nama_pendidikan',
    ];

    // ─── Relations ───────────────────────────────────────────────

    public function karyawans(): HasMany
    {
        return $this->hasMany(Karyawan::class, 'id_pendidikan', 'id_pendidikan');
    }
}
