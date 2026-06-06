<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\User;

use App\Models\NotifikasiKenaikan;
class KenaikanJabatan extends Model
{
    protected $fillable = [
        'karyawan_id',
        'jabatan_lama_id',
        'jabatan_baru_id',
        'tanggal_berlaku',
        'tanggal_berikutnya',
        'status',
        'catatan',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'tanggal_berlaku'     => 'date',
        'tanggal_berikutnya'  => 'date',
        'approved_at'         => 'datetime',
    ];

    // ── Relasi ───────────────────────────────────────────────────────────────

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function jabatanLama()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_lama_id');
    }

    public function jabatanBaru()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_baru_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ── Scope ────────────────────────────────────────────────────────────────

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
     * Approve pengajuan kenaikan jabatan.
     * Setelah approve, update jabatan & tanggal berikutnya di tabel karyawans.
     *
     * @param  int    $adminId
     * @param  int    $jabatanBaruId
     * @param  string $tanggalBerlaku    format: Y-m-d  (tanggal mulai jabatan baru)
     * @param  string $tanggalBerikutnya format: Y-m-d
     * @param  string|null $catatan
     */
    public function approve(
        int $adminId,
        int $jabatanBaruId,
        string $tanggalBerlaku,
        string $tanggalBerikutnya,
        ?string $catatan = null
    ): void {
        $this->update([
            'jabatan_baru_id'     => $jabatanBaruId,
            'tanggal_berlaku'     => $tanggalBerlaku,
            'tanggal_berikutnya'  => $tanggalBerikutnya,
            'status'              => 'approved',
            'catatan'             => $catatan,
            'approved_by'         => $adminId,
            'approved_at'         => now(),
        ]);

        // Sync ke tabel karyawans
        $this->karyawan->update([
            'jabatan_id'                              => $jabatanBaruId,
            'tanggal_mulai_jabatan'                   => $tanggalBerlaku,
            'tanggal_kenaikan_jabatan_berikutnya'     => $tanggalBerikutnya,
        ]);

        // Tandai notifikasi sebagai sudah dibaca
        NotifikasiKenaikan::where('karyawan_id', $this->karyawan_id)
            ->where('tipe', 'jabatan')
            ->update(['sudah_dibaca' => true]);
    }

    /**
     * Reject pengajuan kenaikan jabatan.
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

        // Hanya reschedule, jabatan tidak berubah
        $this->karyawan->update([
            'tanggal_kenaikan_jabatan_berikutnya' => $tanggalBerikutnya,
        ]);

        // Tandai notifikasi sebagai sudah dibaca
        NotifikasiKenaikan::where('karyawan_id', $this->karyawan_id)
            ->where('tipe', 'jabatan')
            ->update(['sudah_dibaca' => true]);
    }
}
