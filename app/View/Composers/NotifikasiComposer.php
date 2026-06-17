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
     *  1. Karyawan aktif dengan jadwal kenaikan berkala (tabel kenaikan_berkalas,
     *     relasi kenaikanBerkalaAktif) yang tanggal_berikutnya dalam 30 hari ke depan.
     *  2. Pengajuan kenaikan golongan (tabel kenaikan_golongans, relasi
     *     kenaikanGolonganAktif) yang statusnya masih pending.
     */
    public function compose(View $view): void
    {
        $today = Carbon::today();
        $batas = $today->copy()->addDays(30);

        // ── Kenaikan Berkala jatuh tempo H-30 ────────────────────────────
        $berkala = Karyawan::with(['jabatan', 'kenaikanBerkalaAktif'])
            ->where('status_aktif', 'Aktif')
            ->whereHas('kenaikanBerkalaAktif', function ($q) use ($today, $batas) {
                $q->whereBetween('tanggal_berikutnya', [$today, $batas]);
            })
            ->get()
            ->map(function ($k) {
                return (object) [
                    'tipe'             => 'berkala',
                    'karyawan'         => $k,
                    'tanggal_kenaikan' => $k->kenaikanBerkalaAktif?->tanggal_berikutnya,
                    'label'            => 'Kenaikan Berkala',
                    'warna'            => 'green',
                ];
            });

        // ── Pengajuan Kenaikan Golongan pending ───────────────────────────
        $golongan = Karyawan::with(['jabatan', 'golongan', 'kenaikanGolonganAktif.golonganBaru'])
            ->where('status_aktif', 'Aktif')
            ->whereHas('kenaikanGolongans', function ($q) {
                $q->pending();
            })
            ->get()
            ->map(function ($k) {
                $pengajuan = $k->kenaikanGolonganAktif;
                return (object) [
                    'tipe'             => 'golongan',
                    'karyawan'         => $k,
                    'tanggal_kenaikan' => $pengajuan?->tanggal_berikutnya,
                    'golongan_baru'    => $pengajuan?->golonganBaru?->nama_golongan,
                    'label'            => 'Kenaikan Golongan',
                    'warna'            => 'violet',
                ];
            });

        // ── Gabungkan & urutkan berdasarkan tanggal terdekat ─────────────
        $notifikasiKenaikan = $berkala->concat($golongan)
            ->sortBy('tanggal_kenaikan')
            ->take(10)
            ->values();

        $view->with([
            'notifikasiKenaikan' => $notifikasiKenaikan,
            'jumlahNotifikasi'   => $notifikasiKenaikan->count(),
        ]);
    }
}
