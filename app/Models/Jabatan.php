<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    protected $primaryKey = 'id_jabatan';

    protected $fillable = [
        'nama_jabatan',
    ];

    // ─── Relations ───────────────────────────────────────────────

    public function karyawans(): HasMany
    {
        return $this->hasMany(Karyawan::class, 'id_jabatan', 'id_jabatan');
    }
}
