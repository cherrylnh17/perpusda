<?php

namespace App\View\Composers;

use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\View\View;

class NotifikasiComposer
{
    /**
     * Dipanggil otomatis setiap kali layouts.navigation di-render.
     * Inject $notifikasiKenaikan dan $jumlahNotifikasi ke semua halaman.
     *
     * Notifikasi terdiri dari:
     *  1. Karyawan aktif dengan tanggal_berkala_berikutnya dalam 30 hari ke depan
     *  2. Pengajuan kenaikan golongan yang masih pending
     */
    public function compose(View $view): void
    {
        $today = Carbon::today();
        $batas = $today->copy()->addDays(30);

        // ── Kenaikan Berkala jatuh tempo H-30 ────────────────────────────
        $berkala = Karyawan::with(['jabatan'])
            ->where('status_aktif', 'Aktif')
            ->whereNotNull('tanggal_berkala_berikutnya')
            ->whereBetween('tanggal_berkala_berikutnya', [$today, $batas])
            ->orderBy('tanggal_berkala_berikutnya')
            ->limit(10)
            ->get()
            ->map(function ($k) {
                return [
                    'tipe'          => 'berkala',
                    'karyawan'      => $k,
                    'tanggal'       => $k->tanggal_berkala_berikutnya,
                    'label'         => 'Kenaikan Berkala',
                    'warna'         => 'green',
                ];
            });

        // ── Pengajuan Kenaikan Golongan pending ───────────────────────────
        $golongan = Karyawan::with(['jabatan', 'golongan', 'pengajuanGolonganPending.golonganBaru'])
            ->where('status_aktif', 'Aktif')
            ->whereHas('pengajuanGolonganPending')
            ->limit(10)
            ->get()
            ->map(function ($k) {
                $pengajuan = $k->pengajuanGolonganPending;
                return [
                    'tipe'          => 'golongan',
                    'karyawan'      => $k,
                    'tanggal'       => $pengajuan?->tanggal_efektif,
                    'golongan_baru' => $pengajuan?->golonganBaru?->nama_golongan,
                    'label'         => 'Kenaikan Golongan',
                    'warna'         => 'violet',
                ];
            });

        // ── Gabungkan & urutkan berdasarkan tanggal terdekat ─────────────
        $notifikasiKenaikan = $berkala->concat($golongan)
            ->sortBy('tanggal')
            ->take(10)
            ->values();

        $view->with([
            'notifikasiKenaikan' => $notifikasiKenaikan,
            'jumlahNotifikasi'   => $notifikasiKenaikan->count(),
        ]);
    }
}
