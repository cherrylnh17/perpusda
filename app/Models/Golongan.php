<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Karyawan;
class Golongan extends Model
{
    protected $table = 'golongans';

    protected $fillable = [
        'tipe',
        'nama_golongan',
    ];

    public function karyawans()
    {
        return $this->hasMany(Karyawan::class);
    }
}
