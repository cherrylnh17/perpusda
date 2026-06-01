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

        // ── Total & rata-rata gaji (hanya karyawan Aktif)
        $totalGaji     = Karyawan::where('status_aktif', 'Aktif')->sum('gaji');
        $rataRataGaji  = Karyawan::where('status_aktif', 'Aktif')->avg('gaji') ?? 0;

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

        // ── Statistik Laki-laki ──────────────────────────────────────────────
        $totalLaki   = Karyawan::where('jenis_kelamin', 'Laki-laki')->count();
        $umurLaki    = $this->distribusiUmur('Laki-laki');

        // ── Statistik Perempuan ──────────────────────────────────────────────
        $totalPerempuan = Karyawan::where('jenis_kelamin', 'Perempuan')->count();
        $umurPerempuan  = $this->distribusiUmur('Perempuan');

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
            'umurLaki',
            'totalPerempuan',
            'umurPerempuan',
        ));
    }

    // ── Helper: distribusi umur per kelompok usia ────────────────────────────
    private function distribusiUmur(string $jenisKelamin): array
    {
        $today = Carbon::today();

        $data = Karyawan::where('jenis_kelamin', $jenisKelamin)
            ->whereNotNull('tanggal_lahir')
            ->get(['tanggal_lahir'])
            ->map(fn($k) => Carbon::parse($k->tanggal_lahir)->diffInYears($today));

        return [
            '20-30' => $data->filter(fn($u) => $u >= 20 && $u <= 30)->count(),
            '31-40' => $data->filter(fn($u) => $u >= 31 && $u <= 40)->count(),
            '41-50' => $data->filter(fn($u) => $u >= 41 && $u <= 50)->count(),
            '50+'   => $data->filter(fn($u) => $u > 50)->count(),
        ];
    }
}
