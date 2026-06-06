<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\JenisKontrak;
use App\Models\Karyawan;
use App\Models\Pendidikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Statistik utama ──────────────────────────────────────────────────
        $totalKaryawan     = Karyawan::count();
        $karyawanAktif     = Karyawan::where('status_aktif', 'Aktif')->count();
        $karyawanCuti      = Karyawan::where('status_aktif', 'Cuti')->count();
        $karyawanPensiun   = Karyawan::where('status_aktif', 'Pensiun')->count();
        $karyawanResign    = Karyawan::where('status_aktif', 'Resign')->count();

        $totalJabatan      = Jabatan::count();
        $totalPendidikan   = Pendidikan::count();
        $totalJenisKontrak = JenisKontrak::count();

        // ── Total & rata-rata gaji (hanya karyawan Aktif) ───────────────────
        $totalGaji    = Karyawan::where('status_aktif', 'Aktif')->sum('gaji');
        $rataRataGaji = Karyawan::where('status_aktif', 'Aktif')->avg('gaji') ?? 0;

        // ── Distribusi karyawan per jabatan (top 6) ─────────────────────────
        // Menggunakan foreign key default Laravel: jabatan_id
        $karyawanPerJabatan = Karyawan::select('jabatans.nama_jabatan', DB::raw('COUNT(*) as total'))
            ->join('jabatans', 'karyawans.jabatan_id', '=', 'jabatans.id')
            ->groupBy('jabatans.nama_jabatan')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        // ── Distribusi karyawan per pendidikan ──────────────────────────────
        // Menggunakan foreign key default Laravel: pendidikan_id
        $karyawanPerPendidikan = Karyawan::select('pendidikans.nama_pendidikan', DB::raw('COUNT(*) as total'))
            ->join('pendidikans', 'karyawans.pendidikan_id', '=', 'pendidikans.id')
            ->groupBy('pendidikans.nama_pendidikan')
            ->orderByDesc('total')
            ->get();

        // ── Distribusi karyawan per jenis kontrak ───────────────────────────
        // Menggunakan foreign key default Laravel: jenis_kontrak_id
        $karyawanPerKontrak = Karyawan::select('jenis_kontraks.nama_kontrak', DB::raw('COUNT(*) as total'))
            ->join('jenis_kontraks', 'karyawans.jenis_kontrak_id', '=', 'jenis_kontraks.id')
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
        // Laki-laki: per jenis kontrak
        $kontrakLaki = Karyawan::select('jenis_kontraks.nama_kontrak', DB::raw('COUNT(*) as total'))
            ->join('jenis_kontraks', 'karyawans.jenis_kontrak_id', '=', 'jenis_kontraks.id')
            ->where('karyawans.jenis_kelamin', 'Laki-laki')
            ->groupBy('jenis_kontraks.nama_kontrak')
            ->orderByDesc('total')
            ->get();

        // Perempuan: per jenis kontrak
        $kontrakPerempuan = Karyawan::select('jenis_kontraks.nama_kontrak', DB::raw('COUNT(*) as total'))
            ->join('jenis_kontraks', 'karyawans.jenis_kontrak_id', '=', 'jenis_kontraks.id')
            ->where('karyawans.jenis_kelamin', 'Perempuan')
            ->groupBy('jenis_kontraks.nama_kontrak')
            ->orderByDesc('total')
            ->get();

        // ── 5 Karyawan aktif dengan kenaikan gaji H-30 terdekat ─────────────
        $today = Carbon::today();
        $batas = $today->copy()->addDays(30);

        $karyawanNaikGaji = Karyawan::with(['jabatan'])
            ->where('status_aktif', 'Aktif')
            ->whereNotNull('tanggal_kenaikan_gaji_berikutnya')
            ->whereBetween('tanggal_kenaikan_gaji_berikutnya', [$today, $batas])
            ->orderBy('tanggal_kenaikan_gaji_berikutnya')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalKaryawan',
            'karyawanAktif',
            'karyawanCuti',
            'karyawanPensiun',
            'karyawanResign',
            'totalJabatan',
            'totalPendidikan',
            'totalJenisKontrak',
            'totalGaji',
            'rataRataGaji',
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
            'karyawanNaikGaji',
        ));
    }
}
