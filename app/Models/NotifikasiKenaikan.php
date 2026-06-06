<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Karyawan;

use App\Models\NotifikasiKenaikan;
class NotifikasiKenaikan extends Model
{
    protected $fillable = [
        'karyawan_id',
        'tipe',
        'tanggal_kenaikan',
        'sudah_dibaca',
    ];

    protected $casts = [
        'tanggal_kenaikan' => 'date',
        'sudah_dibaca'     => 'boolean',
    ];

    // ── Relasi ───────────────────────────────────────────────────────────────

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    // ── Scope: hanya yang belum dibaca (masih tampil di popup) ───────────────

    public function scopeBelumDibaca($query)
    {
        return $query->where('sudah_dibaca', false);
    }

    public function scopeGaji($query)
    {
        return $query->where('tipe', 'gaji');
    }

    public function scopeJabatan($query)
    {
        return $query->where('tipe', 'jabatan');
    }

    // ── Helper: tandai sudah dibaca ──────────────────────────────────────────

    public function markAsRead(): void
    {
        $this->update(['sudah_dibaca' => true]);
    }

    // ── Static: generate notifikasi H-30 (dipanggil dari Scheduler) ─────────

    /**
     * Cek semua karyawan aktif, buat notifikasi jika H-30 sebelum kenaikan.
     * Dipanggil dari App\Console\Kernel (schedule harian).
     */
    /**
     * Isi notifikasi untuk SEMUA karyawan dalam rentang H-1 s/d H-30.
     * Gunakan ini untuk setup awal atau reset data notifikasi.
     * Dipanggil via: php artisan tinker --execute="\App\Models\NotifikasiKenaikan::generateRange();"
     *
     * @param int $hariMulai  default 1  (H-1)
     * @param int $hariAkhir  default 30 (H-30)
     */
    public static function generateRange(int $hariMulai = 1, int $hariAkhir = 30): void
    {
        $today = now()->startOfDay();
        $dari  = $today->copy()->addDays($hariMulai);
        $sampai = $today->copy()->addDays($hariAkhir);

        // Notifikasi kenaikan GAJI (range)
        Karyawan::where('status_aktif', true)
            ->whereNotNull('tanggal_kenaikan_gaji_berikutnya')
            ->whereBetween('tanggal_kenaikan_gaji_berikutnya', [$dari, $sampai])
            ->each(function (Karyawan $karyawan) {
                static::updateOrCreate(
                    [
                        'karyawan_id'      => $karyawan->id,
                        'tipe'             => 'gaji',
                        'tanggal_kenaikan' => $karyawan->tanggal_kenaikan_gaji_berikutnya,
                    ],
                    [
                        'sudah_dibaca' => false,
                    ]
                );
            });

        // Notifikasi kenaikan JABATAN (range)
        Karyawan::where('status_aktif', true)
            ->whereNotNull('tanggal_kenaikan_jabatan_berikutnya')
            ->whereBetween('tanggal_kenaikan_jabatan_berikutnya', [$dari, $sampai])
            ->each(function (Karyawan $karyawan) {
                static::updateOrCreate(
                    [
                        'karyawan_id'      => $karyawan->id,
                        'tipe'             => 'jabatan',
                        'tanggal_kenaikan' => $karyawan->tanggal_kenaikan_jabatan_berikutnya,
                    ],
                    [
                        'sudah_dibaca' => false,
                    ]
                );
            });
    }

    public static function generateH30(): void
    {
        $targetTanggal = now()->addDays(30)->toDateString();

        // Notifikasi kenaikan GAJI
        Karyawan::where('status_aktif', true)
            ->whereNotNull('tanggal_kenaikan_gaji_berikutnya')
            ->whereDate('tanggal_kenaikan_gaji_berikutnya', $targetTanggal)
            ->each(function (Karyawan $karyawan) use ($targetTanggal) {
                // updateOrCreate → tidak duplikat jika scheduler jalan 2x
                static::updateOrCreate(
                    [
                        'karyawan_id'      => $karyawan->id,
                        'tipe'             => 'gaji',
                        'tanggal_kenaikan' => $targetTanggal,
                    ],
                    [
                        'sudah_dibaca' => false,
                    ]
                );
            });

        // Notifikasi kenaikan JABATAN
        Karyawan::where('status_aktif', true)
            ->whereNotNull('tanggal_kenaikan_jabatan_berikutnya')
            ->whereDate('tanggal_kenaikan_jabatan_berikutnya', $targetTanggal)
            ->each(function (Karyawan $karyawan) use ($targetTanggal) {
                static::updateOrCreate(
                    [
                        'karyawan_id'      => $karyawan->id,
                        'tipe'             => 'jabatan',
                        'tanggal_kenaikan' => $targetTanggal,
                    ],
                    [
                        'sudah_dibaca' => false,
                    ]
                );
            });
    }
}
