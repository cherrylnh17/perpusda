<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class KenaikanGolongan extends Model
{
    protected $primaryKey = 'id_golongan_kenaikan';

    protected $fillable = [
        'id_karyawan',
        'golongan_lama_id',
        'golongan_baru_id',
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
        return $this->hasOne(HistoriGolongan::class, 'id_golongan_kenaikan', 'id_golongan_kenaikan');
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

    public function sudahJatuhTempo(): bool
    {
        return now()->gte($this->tanggal_berikutnya);
    }

    /**
     * Approve: update status + update golongan karyawan + insert histori.
     * $tanggalBerikutnya null berarti sudah golongan teratas (tidak insert scheduled baru).
     * Panggil di dalam DB::transaction.
     */
    public function approve(User $user, Golongan $golonganBaru, ?string $tanggalBerikutnya = null, ?string $catatan = null): ?static
    {
        $karyawan = $this->karyawan;

        // Update record ini
        $this->update([
            'status'          => 'diterima',
            'golongan_lama_id' => $karyawan->id_golongan,
            'golongan_baru_id' => $golonganBaru->id_golongan,
            'diproses_oleh'   => $user->id,
            'diproses_pada'   => now(),
            'catatan'         => $catatan,
        ]);

        // Update golongan aktif karyawan
        $karyawan->update([
            'id_golongan' => $golonganBaru->id_golongan,
        ]);

        // Catat ke histori
        HistoriGolongan::create([
            'id_karyawan'         => $this->id_karyawan,
            'golongan_lama_id'    => $this->golongan_lama_id,
            'golongan_baru_id'    => $golonganBaru->id_golongan,
            'tanggal_efektif'     => $this->tanggal_berikutnya,
            'id_golongan_kenaikan' => $this->id_golongan_kenaikan,
            'dicatat_oleh'        => $user->id,
        ]);

        // Insert jadwal berikutnya jika admin set tanggal
        if ($tanggalBerikutnya) {
            return static::create([
                'id_karyawan'        => $this->id_karyawan,
                'tanggal_berikutnya' => $tanggalBerikutnya,
                'status'             => 'scheduled',
            ]);
        }

        return null; // Golongan teratas, tidak ada jadwal berikutnya
    }

    /**
     * Stop: sudah golongan teratas atau admin hentikan.
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
