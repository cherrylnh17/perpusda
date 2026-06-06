<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Karyawan;
class JenisKontrak extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kontrak',
        'jam_kerja_sehari',
    ];

    public function karyawans()
    {
        return $this->hasMany(Karyawan::class);
    }
}
