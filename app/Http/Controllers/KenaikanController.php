<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Golongan;
use App\Models\PengajuanKenaikanBerkala;
use App\Models\PengajuanKenaikanGolongan;
use App\Models\HistoriGolongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'pengajuanBerkalaPending',
            'pengajuanGolonganPending.golonganBaru',
        ])->where('status_aktif', 'Aktif');

        // ── Filter tipe kenaikan ─────────────────────────────────────────────
        $tipe    = $request->input('tipe', 'semua'); // semua | berkala | golongan
        $rentang = $request->input('rentang', '30'); // 7 | 14 | 30 | semua
        $today   = Carbon::today();

        if ($rentang !== 'semua') {
            $batas = $today->copy()->addDays((int) $rentang);

            if ($tipe === 'berkala') {
                $query->whereBetween('tanggal_berkala_berikutnya', [$today, $batas]);

            } elseif ($tipe === 'golongan') {
                $query->whereHas('pengajuanGolonganPending');

            } else {
                // semua: berkala ATAU ada pengajuan golongan pending
                $query->where(function ($q) use ($today, $batas) {
                    $q->whereBetween('tanggal_berkala_berikutnya', [$today, $batas])
                      ->orWhereHas('pengajuanGolonganPending');
                });
            }
        } else {
            if ($tipe === 'berkala') {
                $query->whereNotNull('tanggal_berkala_berikutnya')
                      ->where('tanggal_berkala_berikutnya', '>=', $today);

            } elseif ($tipe === 'golongan') {
                $query->whereHas('pengajuanGolonganPending');

            } else {
                $query->where(function ($q) use ($today) {
                    $q->where('tanggal_berkala_berikutnya', '>=', $today)
                      ->orWhereHas('pengajuanGolonganPending');
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

        // ── Urutkan: yang paling dekat jadwal berkala tampil di atas ─────────
        $query->orderByRaw("
            COALESCE(tanggal_berkala_berikutnya, '9999-12-31') ASC
        ");

        $karyawans = $query->paginate(15)->withQueryString();

        // ── Summary count ────────────────────────────────────────────────────
        $batas30 = $today->copy()->addDays(30);

        $totalBerkalaH30 = Karyawan::where('status_aktif', 'Aktif')
            ->whereBetween('tanggal_berkala_berikutnya', [$today, $batas30])
            ->count();

        $totalGolonganPending = Karyawan::where('status_aktif', 'Aktif')
            ->whereHas('pengajuanGolonganPending')
            ->count();

        $totalSemuaH30 = Karyawan::where('status_aktif', 'Aktif')
            ->where(function ($q) use ($today, $batas30) {
                $q->whereBetween('tanggal_berkala_berikutnya', [$today, $batas30])
                  ->orWhereHas('pengajuanGolonganPending');
            })->count();

        // ── Daftar golongan untuk modal approve golongan ─────────────────────
        $golongans = Golongan::orderBy('tipe')->orderBy('nama_golongan')->get();

        return view('kenaikan.index', compact(
            'karyawans',
            'golongans',
            'totalBerkalaH30',
            'totalGolonganPending',
            'totalSemuaH30',
            'tipe',
            'rentang',
        ));
    }

    // ── APPROVE BERKALA ───────────────────────────────────────────────────────

    public function approveBerkala(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'tanggal_efektif'    => 'required|date',
            'tanggal_berikutnya' => 'required|date|after:tanggal_efektif',
            'catatan'            => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $karyawan) {
            // Cari atau buat pengajuan pending
            $pengajuan = PengajuanKenaikanBerkala::firstOrCreate(
                ['id_karyawan' => $karyawan->id_karyawan, 'status' => 'pending'],
                ['tanggal_efektif' => $request->tanggal_efektif]
            );

            // Tandai diterima
            $pengajuan->update([
                'status'        => 'diterima',
                'catatan'       => $request->catatan,
                'diproses_oleh' => Auth::id(),
                'diproses_pada' => now(),
            ]);

            // Update jadwal berkala di karyawan
            $karyawan->update([
                'tanggal_berkala_terakhir'   => $request->tanggal_efektif,
                'tanggal_berkala_berikutnya' => $request->tanggal_berikutnya,
            ]);
        });

        return redirect()->route('kenaikan.index', $request->only(['tipe', 'rentang', 'search']))
            ->with('success', "Kenaikan berkala {$karyawan->nama_lengkap} berhasil disetujui.");
    }

    // ── REJECT BERKALA ────────────────────────────────────────────────────────

    public function rejectBerkala(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'tanggal_berikutnya' => 'required|date|after:today',
            'catatan'            => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $karyawan) {
            $pengajuan = PengajuanKenaikanBerkala::firstOrCreate(
                ['id_karyawan' => $karyawan->id_karyawan, 'status' => 'pending'],
                ['tanggal_efektif' => $karyawan->tanggal_berkala_berikutnya]
            );

            $pengajuan->update([
                'status'        => 'ditolak',
                'catatan'       => $request->catatan,
                'diproses_oleh' => Auth::id(),
                'diproses_pada' => now(),
            ]);

            // Jadwal ulang saja, berkala terakhir tidak berubah
            $karyawan->update([
                'tanggal_berkala_berikutnya' => $request->tanggal_berikutnya,
            ]);
        });

        return redirect()->route('kenaikan.index', $request->only(['tipe', 'rentang', 'search']))
            ->with('success', "Kenaikan berkala {$karyawan->nama_lengkap} ditolak & dijadwal ulang.");
    }

    // ── APPROVE GOLONGAN ──────────────────────────────────────────────────────

    public function approveGolongan(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'golongan_baru_id'   => 'required|exists:golongans,id_golongan',
            'tanggal_efektif'    => 'required|date',
            'tanggal_berikutnya' => 'required|date|after:tanggal_efektif',
            'catatan'            => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $karyawan) {
            $pengajuan = PengajuanKenaikanGolongan::firstOrCreate(
                ['id_karyawan' => $karyawan->id_karyawan, 'status' => 'pending'],
                [
                    'golongan_lama_id' => $karyawan->id_golongan,
                    'golongan_baru_id' => $request->golongan_baru_id,
                    'tanggal_efektif'  => $request->tanggal_efektif,
                ]
            );

            $pengajuan->update([
                'golongan_baru_id' => $request->golongan_baru_id,
                'tanggal_efektif'  => $request->tanggal_efektif,
                'status'           => 'diterima',
                'catatan'          => $request->catatan,
                'diproses_oleh'    => Auth::id(),
                'diproses_pada'    => now(),
            ]);

            // Catat histori
            HistoriGolongan::create([
                'id_karyawan'           => $karyawan->id_karyawan,
                'golongan_lama_id'      => $pengajuan->golongan_lama_id,
                'golongan_baru_id'      => $request->golongan_baru_id,
                'tanggal_efektif'       => $request->tanggal_efektif,
                'id_pengajuan_golongan' => $pengajuan->id_pengajuan_golongan,
                'dicatat_oleh'          => Auth::id(),
            ]);

            // Update golongan & jadwal berkala berikutnya di karyawan
            $karyawan->update([
                'id_golongan'                => $request->golongan_baru_id,
                'tanggal_mulai_golongan'     => $request->tanggal_efektif,
                'tanggal_berkala_berikutnya' => $request->tanggal_berikutnya,
            ]);
        });

        return redirect()->route('kenaikan.index', $request->only(['tipe', 'rentang', 'search']))
            ->with('success', "Kenaikan golongan {$karyawan->nama_lengkap} berhasil disetujui.");
    }

    // ── REJECT GOLONGAN ───────────────────────────────────────────────────────

    public function rejectGolongan(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $karyawan) {
            $pengajuan = PengajuanKenaikanGolongan::where('id_karyawan', $karyawan->id_karyawan)
                ->where('status', 'pending')
                ->first();

            if ($pengajuan) {
                $pengajuan->update([
                    'status'        => 'ditolak',
                    'catatan'       => $request->catatan,
                    'diproses_oleh' => Auth::id(),
                    'diproses_pada' => now(),
                ]);
            }
        });

        return redirect()->route('kenaikan.index', $request->only(['tipe', 'rentang', 'search']))
            ->with('success', "Pengajuan kenaikan golongan {$karyawan->nama_lengkap} ditolak.");
    }
}
