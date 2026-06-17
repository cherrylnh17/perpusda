<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\JenisKontrak;
use App\Models\Karyawan;
use App\Models\KenaikanBerkala;
use App\Models\KenaikanGolongan;
use App\Models\Pendidikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Statistik utama ──────────────────────────────────────────────────
        $totalKaryawan   = Karyawan::count();
        $karyawanAktif   = Karyawan::where('status_aktif', 'Aktif')->count();
        $karyawanPensiun = Karyawan::where('status_aktif', 'Pensiun')->count();

        $totalJabatan      = Jabatan::count();
        $totalPendidikan   = Pendidikan::count();
        $totalJenisKontrak = JenisKontrak::count();

        // ── Distribusi karyawan per jabatan (top 6) ─────────────────────────
        $karyawanPerJabatan = Karyawan::select('jabatans.nama_jabatan', DB::raw('COUNT(*) as total'))
            ->join('jabatans', 'karyawans.id_jabatan', '=', 'jabatans.id_jabatan')
            ->groupBy('jabatans.nama_jabatan')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        // ── Distribusi karyawan per pendidikan ──────────────────────────────
        $karyawanPerPendidikan = Karyawan::select('pendidikans.nama_pendidikan', DB::raw('COUNT(*) as total'))
            ->join('pendidikans', 'karyawans.id_pendidikan', '=', 'pendidikans.id_pendidikan')
            ->groupBy('pendidikans.nama_pendidikan')
            ->orderByDesc('total')
            ->get();

        // ── Distribusi karyawan per jenis kontrak ───────────────────────────
        $karyawanPerKontrak = Karyawan::select('jenis_kontraks.nama_kontrak', DB::raw('COUNT(*) as total'))
            ->join('jenis_kontraks', 'karyawans.id_jenis_kontrak', '=', 'jenis_kontraks.id_jenis_kontrak')
            ->groupBy('jenis_kontraks.nama_kontrak')
            ->orderByDesc('total')
            ->get();

        // ── Karyawan terbaru (5 data) ────────────────────────────────────────
        $karyawanTerbaru = Karyawan::with(['jabatan', 'jenisKontrak'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // ── Statistik gender ─────────────────────────────────────────────────
        $totalLaki      = Karyawan::where('jenis_kelamin', 'Laki-laki')->count();
        $totalPerempuan = Karyawan::where('jenis_kelamin', 'Perempuan')->count();

        // ── PNS / PPPK per gender (via golongan) ─────────────────────────────
        $pnsLaki = Karyawan::where('jenis_kelamin', 'Laki-laki')
            ->whereHas('golongan', fn($q) => $q->where('tipe', 'PNS'))
            ->count();

        $pnsPerempuan = Karyawan::where('jenis_kelamin', 'Perempuan')
            ->whereHas('golongan', fn($q) => $q->where('tipe', 'PNS'))
            ->count();

        $pppkLaki = Karyawan::where('jenis_kelamin', 'Laki-laki')
            ->whereHas('golongan', fn($q) => $q->where('tipe', 'PPPK'))
            ->count();

        $pppkPerempuan = Karyawan::where('jenis_kelamin', 'Perempuan')
            ->whereHas('golongan', fn($q) => $q->where('tipe', 'PPPK'))
            ->count();

        // ── Distribusi jenis kontrak per gender ──────────────────────────────
        $kontrakLaki = Karyawan::select('jenis_kontraks.nama_kontrak', DB::raw('COUNT(*) as total'))
            ->join('jenis_kontraks', 'karyawans.id_jenis_kontrak', '=', 'jenis_kontraks.id_jenis_kontrak')
            ->where('karyawans.jenis_kelamin', 'Laki-laki')
            ->groupBy('jenis_kontraks.nama_kontrak')
            ->orderByDesc('total')
            ->get();

        $kontrakPerempuan = Karyawan::select('jenis_kontraks.nama_kontrak', DB::raw('COUNT(*) as total'))
            ->join('jenis_kontraks', 'karyawans.id_jenis_kontrak', '=', 'jenis_kontraks.id_jenis_kontrak')
            ->where('karyawans.jenis_kelamin', 'Perempuan')
            ->groupBy('jenis_kontraks.nama_kontrak')
            ->orderByDesc('total')
            ->get();

        // ── 5 Karyawan aktif dengan kenaikan berkala H-30 terdekat ──────────
        // Berdasarkan jadwal aktif (scheduled/pending) di tabel kenaikan_berkalas
        $today = Carbon::today();
        $batas = $today->copy()->addDays(30);

        $karyawanNaikBerkala = Karyawan::with([
                'jabatan',
                'kenaikanBerkalaAktif',
            ])
            ->where('status_aktif', 'Aktif')
            ->whereHas('kenaikanBerkalaAktif', function ($q) use ($today, $batas) {
                $q->whereBetween('tanggal_berikutnya', [$today, $batas]);
            })
            ->orderBy(
                \App\Models\KenaikanBerkala::select('tanggal_berikutnya')
                    ->whereColumn('id_karyawan', 'karyawans.id_karyawan')
                    ->whereIn('status', ['scheduled', 'pending'])
                    ->orderBy('tanggal_berikutnya')
                    ->limit(1)
            )
            ->limit(5)
            ->get();

        // ── 5 Karyawan aktif dengan kenaikan golongan pending ────────────────
        // Diambil dari kenaikan_golongans status pending, diurutkan tanggal terdekat
        $karyawanNaikGolongan = Karyawan::with([
                'jabatan',
                'golongan',
                'kenaikanGolonganAktif.golonganLama',
                'kenaikanGolonganAktif.golonganBaru',
            ])
            ->where('status_aktif', 'Aktif')
            ->whereHas('kenaikanGolonganAktif', fn ($q) => $q->where('status', 'pending'))
            ->orderBy(
                \App\Models\KenaikanGolongan::select('tanggal_berikutnya')
                    ->whereColumn('id_karyawan', 'karyawans.id_karyawan')
                    ->where('status', 'pending')
                    ->orderBy('tanggal_berikutnya')
                    ->limit(1)
            )
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalKaryawan',
            'karyawanAktif',
            'karyawanPensiun',
            'totalJabatan',
            'totalPendidikan',
            'totalJenisKontrak',
            'karyawanPerJabatan',
            'karyawanPerPendidikan',
            'karyawanPerKontrak',
            'karyawanTerbaru',
            'totalLaki',
            'totalPerempuan',
            'pnsLaki',
            'pnsPerempuan',
            'pppkLaki',
            'pppkPerempuan',
            'kontrakLaki',
            'kontrakPerempuan',
            'karyawanNaikBerkala',
            'karyawanNaikGolongan',
        ));
    }
}
