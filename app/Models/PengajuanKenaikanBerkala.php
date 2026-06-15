<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanKenaikanBerkala extends Model
{
    protected $primaryKey = 'id_pengajuan_berkala';

    protected $fillable = [
        'id_karyawan',
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

    public function diprosesByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diproses_oleh', 'id');
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
     * Panggil ini di dalam DB::transaction bersama update karyawan.
     */
    public function terima(User $user): void
    {
        $this->update([
            'status'       => 'diterima',
            'diproses_oleh' => $user->id,
            'diproses_pada' => now(),
        ]);
    }
}
