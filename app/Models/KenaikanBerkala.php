<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KenaikanBerkala extends Model
{
    protected $primaryKey = 'id_berkala';

    protected $fillable = [
        'id_karyawan',
        'tanggal_berikutnya',
        'status',
        'catatan',
        'diproses_oleh',
        'diproses_pada',
    ];

    protected $casts = [
        'tanggal_berikutnya' => 'date',
        'diproses_pada'      => 'datetime',
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

    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isDiterima(): bool
    {
        return $this->status === 'diterima';
    }

    public function isStop(): bool
    {
        return $this->status === 'stop';
    }

    /** Sudah waktunya → cron akan set ke pending */
    public function sudahJatuhTempo(): bool
    {
        return now()->gte($this->tanggal_berikutnya);
    }

    /**
     * Approve: update status + catat user + insert row scheduled berikutnya (+2 tahun).
     * Panggil di dalam DB::transaction.
     */
    public function approve(User $user): static
    {
        $this->update([
            'status'        => 'diterima',
            'diproses_oleh' => $user->id,
            'diproses_pada' => now(),
        ]);

        // Insert jadwal berkala berikutnya
        return static::create([
            'id_karyawan'        => $this->id_karyawan,
            'tanggal_berikutnya' => $this->tanggal_berikutnya->addYears(2),
            'status'             => 'scheduled',
        ]);
    }

    /**
     * Stop: sudah maksimal, tidak ada jadwal berikutnya.
     * Panggil di dalam DB::transaction.
     */
    public function stop(User $user, ?string $catatan = null): void
    {
        $this->update([
            'status'        => 'stop',
            'diproses_oleh' => $user->id,
            'diproses_pada' => now(),
            'catatan'       => $catatan,
        ]);
    }

    // ─── Scopes ──────────────────────────────────────────────────

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /** Scope untuk cron: scheduled yang sudah jatuh tempo */
    public function scopeJatuhTempo($query)
    {
        return $query->where('status', 'scheduled')
            ->whereDate('tanggal_berikutnya', '<=', now());
    }
}
