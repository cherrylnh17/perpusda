<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendidikan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pendidikan';

    protected $fillable = [
        'nama_pendidikan',
    ];

    public function karyawans()
    {
        return $this->hasMany(Karyawan::class, 'id_pendidikan', 'id_pendidikan');
    }
}
