<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoriGolongan extends Model
{
    protected $primaryKey = 'id_histori';

    protected $fillable = [
        'id_karyawan',
        'golongan_lama_id',
        'golongan_baru_id',
        'tanggal_efektif',
        'id_pengajuan_golongan',
        'dicatat_oleh',
    ];

    protected $casts = [
        'tanggal_efektif' => 'date',
    ];

    // ─── Relations ───────────────────────────────────────────────

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }

    public function golonganLama(): BelongsTo
    {
        return $this->belongsTo(Golongan::class, 'golongan_lama_id', 'id_golongan');
    }

    public function golonganBaru(): BelongsTo
    {
        return $this->belongsTo(Golongan::class, 'golongan_baru_id', 'id_golongan');
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanKenaikanGolongan::class, 'id_pengajuan_golongan', 'id_pengajuan_golongan');
    }

    public function dicatatByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh', 'id');
    }
}
