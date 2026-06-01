<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKontrak extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_jenis_kontrak';

    protected $fillable = [
        'nama_kontrak',
        'jam_kerja_sehari',
    ];

    public function karyawans()
    {
        return $this->hasMany(Karyawan::class, 'id_jenis_kontrak', 'id_jenis_kontrak');
    }
}
