<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Karyawan;
use App\Models\User;

use App\Models\NotifikasiKenaikan;
class KenaikanGaji extends Model
{
    protected $fillable = [
        'karyawan_id',
        'gaji_lama',
        'gaji_baru',
        'tanggal_berlaku',
        'tanggal_berikutnya',
        'status',
        'catatan',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'gaji_lama'           => 'decimal:2',
        'gaji_baru'           => 'decimal:2',
        'tanggal_berlaku'     => 'date',
        'tanggal_berikutnya'  => 'date',
        'approved_at'         => 'datetime',
    ];

    // ── Relasi ───────────────────────────────────────────────────────────────

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ── Scope: filter berdasarkan status ────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // ── Helper: approve pengajuan ────────────────────────────────────────────

    /**
     * Approve pengajuan kenaikan gaji.
     * Setelah approve, update gaji & tanggal berikutnya di tabel karyawans.
     *
     * @param  int    $adminId
     * @param  float  $gajiBaru
     * @param  string $tanggalBerikutnya  format: Y-m-d
     * @param  string|null $catatan
     */
    public function approve(int $adminId, float $gajiBaru, string $tanggalBerikutnya, ?string $catatan = null): void
    {
        $this->update([
            'gaji_baru'           => $gajiBaru,
            'tanggal_berikutnya'  => $tanggalBerikutnya,
            'status'              => 'approved',
            'catatan'             => $catatan,
            'approved_by'         => $adminId,
            'approved_at'         => now(),
        ]);

        // Sync ke tabel karyawans
        $this->karyawan->update([
            'gaji'                                => $gajiBaru,
            'tanggal_kenaikan_gaji_berikutnya'    => $tanggalBerikutnya,
        ]);

        // Tandai notifikasi sebagai sudah dibaca
        NotifikasiKenaikan::where('karyawan_id', $this->karyawan_id)
            ->where('tipe', 'gaji')
            ->update(['sudah_dibaca' => true]);
    }

    /**
     * Reject pengajuan kenaikan gaji.
     * Setelah reject, hanya reschedule tanggal berikutnya di tabel karyawans.
     *
     * @param  int    $adminId
     * @param  string $tanggalBerikutnya  format: Y-m-d
     * @param  string|null $catatan
     */
    public function reject(int $adminId, string $tanggalBerikutnya, ?string $catatan = null): void
    {
        $this->update([
            'tanggal_berikutnya'  => $tanggalBerikutnya,
            'status'              => 'rejected',
            'catatan'             => $catatan,
            'approved_by'         => $adminId,
            'approved_at'         => now(),
        ]);

        // Hanya update jadwal, gaji tidak berubah
        $this->karyawan->update([
            'tanggal_kenaikan_gaji_berikutnya' => $tanggalBerikutnya,
        ]);

        // Tandai notifikasi sebagai sudah dibaca
        NotifikasiKenaikan::where('karyawan_id', $this->karyawan_id)
            ->where('tipe', 'gaji')
            ->update(['sudah_dibaca' => true]);
    }
}
