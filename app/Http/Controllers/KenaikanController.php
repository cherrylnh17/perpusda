<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\KenaikanGaji;
use App\Models\KenaikanJabatan;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KenaikanController extends Controller
{
    // ── INDEX: daftar karyawan dengan countdown kenaikan ─────────────────────

    public function index(Request $request)
    {
        $query = Karyawan::with([
            'jabatan',
            'golongan',
            'jenisKontrak',
            'kenaikanGajiPending',
            'kenaikanJabatanPending',
        ])->where('status_aktif', 'Aktif');

        // ── Filter tipe kenaikan ─────────────────────────────────────────────
        $tipe = $request->input('tipe', 'semua'); // semua | gaji | jabatan

        // ── Filter rentang hari ──────────────────────────────────────────────
        $rentang = $request->input('rentang', '30'); // 7 | 14 | 30 | semua
        $today   = Carbon::today();

        if ($rentang !== 'semua') {
            $batas = $today->copy()->addDays((int) $rentang);

            if ($tipe === 'gaji') {
                $query->whereBetween('tanggal_kenaikan_gaji_berikutnya', [$today, $batas]);

            } elseif ($tipe === 'jabatan') {
                $query->whereBetween('tanggal_kenaikan_jabatan_berikutnya', [$today, $batas]);

            } else {
                // semua: gaji ATAU jabatan dalam rentang
                $query->where(function ($q) use ($today, $batas) {
                    $q->whereBetween('tanggal_kenaikan_gaji_berikutnya', [$today, $batas])
                      ->orWhereBetween('tanggal_kenaikan_jabatan_berikutnya', [$today, $batas]);
                });
            }
        } else {
            // Rentang "semua" → tetap filter supaya hanya yang punya jadwal tampil
            if ($tipe === 'gaji') {
                $query->whereNotNull('tanggal_kenaikan_gaji_berikutnya')
                      ->where('tanggal_kenaikan_gaji_berikutnya', '>=', $today);

            } elseif ($tipe === 'jabatan') {
                $query->whereNotNull('tanggal_kenaikan_jabatan_berikutnya')
                      ->where('tanggal_kenaikan_jabatan_berikutnya', '>=', $today);

            } else {
                $query->where(function ($q) use ($today) {
                    $q->where('tanggal_kenaikan_gaji_berikutnya', '>=', $today)
                      ->orWhere('tanggal_kenaikan_jabatan_berikutnya', '>=', $today);
                });
            }
        }

        // ── Filter search nama / NIP ─────────────────────────────────────────
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_lengkap', 'like', "%{$s}%")
                  ->orWhere('nip', 'like', "%{$s}%");
            });
        }

        // ── Urutkan: yang paling dekat jadwalnya tampil di atas ─────────────
        // Gunakan LEAST() agar baris dengan 2 kenaikan tetap terurut oleh yang paling dekat
        $query->orderByRaw("
            LEAST(
                COALESCE(tanggal_kenaikan_gaji_berikutnya, '9999-12-31'),
                COALESCE(tanggal_kenaikan_jabatan_berikutnya, '9999-12-31')
            ) ASC
        ");

        $karyawans = $query->paginate(15)->withQueryString();

        // ── Summary count (untuk kartu di atas) ─────────────────────────────
        $batas30 = $today->copy()->addDays(30);

        $totalGajiH30 = Karyawan::where('status_aktif', 'Aktif')
            ->whereBetween('tanggal_kenaikan_gaji_berikutnya', [$today, $batas30])
            ->count();

        $totalJabatanH30 = Karyawan::where('status_aktif', 'Aktif')
            ->whereBetween('tanggal_kenaikan_jabatan_berikutnya', [$today, $batas30])
            ->count();

        $totalSemuaH30 = Karyawan::where('status_aktif', 'Aktif')
            ->where(function ($q) use ($today, $batas30) {
                $q->whereBetween('tanggal_kenaikan_gaji_berikutnya', [$today, $batas30])
                  ->orWhereBetween('tanggal_kenaikan_jabatan_berikutnya', [$today, $batas30]);
            })->count();

        // ── Data untuk modal approve jabatan: daftar jabatan ─────────────────
        $jabatans = Jabatan::orderBy('nama_jabatan')->get();

        return view('kenaikan.index', compact(
            'karyawans',
            'jabatans',
            'totalGajiH30',
            'totalJabatanH30',
            'totalSemuaH30',
            'tipe',
            'rentang',
        ));
    }

    // ── APPROVE GAJI ──────────────────────────────────────────────────────────

    public function approveGaji(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'gaji_baru'           => 'required|numeric|min:0',
            'tanggal_berlaku'     => 'required|date',
            'tanggal_berikutnya'  => 'required|date|after:tanggal_berlaku',
            'catatan'             => 'nullable|string|max:500',
        ]);

        // Buat / ambil pengajuan pending — jika belum ada, buat baru
        $pengajuan = KenaikanGaji::firstOrCreate(
            [
                'karyawan_id' => $karyawan->id,
                'status'      => 'pending',
            ],
            [
                'gaji_lama'       => $karyawan->gaji,
                'gaji_baru'       => $request->gaji_baru,
                'tanggal_berlaku' => $request->tanggal_berlaku,
            ]
        );

        $pengajuan->approve(
            adminId:           Auth::id(),
            gajiBaru:          (float) $request->gaji_baru,
            tanggalBerikutnya: $request->tanggal_berikutnya,
            catatan:           $request->catatan,
        );

        return redirect()->route('kenaikan.index', $request->only(['tipe', 'rentang', 'search']))
            ->with('success', "Kenaikan gaji {$karyawan->nama_lengkap} berhasil di-approve.");
    }

    // ── REJECT GAJI ───────────────────────────────────────────────────────────

    public function rejectGaji(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'tanggal_berikutnya' => 'required|date|after:today',
            'catatan'            => 'nullable|string|max:500',
        ]);

        $pengajuan = KenaikanGaji::firstOrCreate(
            [
                'karyawan_id' => $karyawan->id,
                'status'      => 'pending',
            ],
            [
                'gaji_lama'       => $karyawan->gaji,
                'gaji_baru'       => $karyawan->gaji,
                'tanggal_berlaku' => $karyawan->tanggal_kenaikan_gaji_berikutnya,
            ]
        );

        $pengajuan->reject(
            adminId:           Auth::id(),
            tanggalBerikutnya: $request->tanggal_berikutnya,
            catatan:           $request->catatan,
        );

        return redirect()->route('kenaikan.index', $request->only(['tipe', 'rentang', 'search']))
            ->with('success', "Kenaikan gaji {$karyawan->nama_lengkap} ditolak & dijadwal ulang.");
    }

    // ── APPROVE JABATAN ───────────────────────────────────────────────────────

    public function approveJabatan(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'jabatan_baru_id'    => 'required|exists:jabatans,id',
            'tanggal_berlaku'    => 'required|date',
            'tanggal_berikutnya' => 'required|date|after:tanggal_berlaku',
            'catatan'            => 'nullable|string|max:500',
        ]);

        $pengajuan = KenaikanJabatan::firstOrCreate(
            [
                'karyawan_id' => $karyawan->id,
                'status'      => 'pending',
            ],
            [
                'jabatan_lama_id' => $karyawan->jabatan_id,
                'jabatan_baru_id' => $request->jabatan_baru_id,
                'tanggal_berlaku' => $request->tanggal_berlaku,
            ]
        );

        $pengajuan->approve(
            adminId:           Auth::id(),
            jabatanBaruId:     (int) $request->jabatan_baru_id,
            tanggalBerlaku:    $request->tanggal_berlaku,
            tanggalBerikutnya: $request->tanggal_berikutnya,
            catatan:           $request->catatan,
        );

        return redirect()->route('kenaikan.index', $request->only(['tipe', 'rentang', 'search']))
            ->with('success', "Kenaikan jabatan {$karyawan->nama_lengkap} berhasil di-approve.");
    }

    // ── REJECT JABATAN ────────────────────────────────────────────────────────

    public function rejectJabatan(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'tanggal_berikutnya' => 'required|date|after:today',
            'catatan'            => 'nullable|string|max:500',
        ]);

        $pengajuan = KenaikanJabatan::firstOrCreate(
            [
                'karyawan_id' => $karyawan->id,
                'status'      => 'pending',
            ],
            [
                'jabatan_lama_id' => $karyawan->jabatan_id,
                'jabatan_baru_id' => $karyawan->jabatan_id,
                'tanggal_berlaku' => $karyawan->tanggal_kenaikan_jabatan_berikutnya,
            ]
        );

        $pengajuan->reject(
            adminId:           Auth::id(),
            tanggalBerikutnya: $request->tanggal_berikutnya,
            catatan:           $request->catatan,
        );

        return redirect()->route('kenaikan.index', $request->only(['tipe', 'rentang', 'search']))
            ->with('success', "Kenaikan jabatan {$karyawan->nama_lengkap} ditolak & dijadwal ulang.");
    }
}
