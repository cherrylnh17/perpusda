<?php

namespace App\View\Composers;

use App\Models\NotifikasiKenaikan;
use Illuminate\View\View;

class NotifikasiComposer
{
    /**
     * Dipanggil otomatis setiap kali layouts.navigation di-render.
     * Inject $notifikasiKenaikan dan $jumlahNotifikasi ke semua halaman.
     */
    public function compose(View $view): void
    {
        $notifikasiKenaikan = NotifikasiKenaikan::with(['karyawan.jabatan'])
            ->belumDibaca()
            ->orderBy('tanggal_kenaikan')
            ->limit(10)
            ->get();

        $view->with([
            'notifikasiKenaikan' => $notifikasiKenaikan,
            'jumlahNotifikasi'   => $notifikasiKenaikan->count(),
        ]);
    }
}
