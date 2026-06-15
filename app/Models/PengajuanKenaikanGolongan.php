<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PengajuanKenaikanGolongan extends Model
{
    protected $primaryKey = 'id_pengajuan_golongan';

    protected $fillable = [
        'id_karyawan',
        'golongan_lama_id',
        'golongan_baru_id',
        'tanggal_efektif',
        'status',
        'catatan',
        'diproses_oleh',
        'diproses_pada',
    ];

    protected $casts = [
        'tanggal_efektif' => 'date',
        'diproses_pada'   => 'datetime',
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

    public function diprosesByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diproses_oleh', 'id');
    }

    public function historiGolongan(): HasOne
    {
        return $this->hasOne(HistoriGolongan::class, 'id_pengajuan_golongan', 'id_pengajuan_golongan');
    }

    // ─── Helpers ─────────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isDiterima(): bool
    {
        return $this->status === 'diterima';
    }

    /**
     * Terima pengajuan: update status + catat siapa & kapan.
     * Panggil di dalam DB::transaction bersama update karyawan & insert histori.
     */
    public function terima(User $user): void
    {
        $this->update([
            'status'        => 'diterima',
            'diproses_oleh' => $user->id,
            'diproses_pada' => now(),
        ]);
    }
}
