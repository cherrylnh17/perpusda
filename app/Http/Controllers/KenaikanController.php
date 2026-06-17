<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Golongan;
use App\Models\KenaikanBerkala;
use App\Models\KenaikanGolongan;
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
        $tipe    = $request->input('tipe', 'semua'); // semua | berkala | golongan
        $rentang = $request->input('rentang', '30'); // 7 | 14 | 30 | semua
        $today   = Carbon::today();

        $query = Karyawan::with([
            'jabatan',
            'golongan',
            'jenisKontrak',
            'kenaikanBerkalaAktif',
            'kenaikanGolonganAktif.golonganBaru',
        ])->where('status_aktif', 'Aktif');

        // ── Filter tipe & rentang ────────────────────────────────────────────
        $batas = $rentang !== 'semua'
            ? $today->copy()->addDays((int) $rentang)
            : null;

        if ($tipe === 'berkala') {
            $query->whereHas('kenaikanBerkalaAktif', function ($q) use ($today, $batas) {
                $q->where('tanggal_berikutnya', '>=', $today);
                if ($batas) $q->where('tanggal_berikutnya', '<=', $batas);
            });

        } elseif ($tipe === 'golongan') {
            $query->whereHas('kenaikanGolonganAktif');

        } else {
            // semua: berkala dalam rentang ATAU ada golongan aktif
            $query->where(function ($q) use ($today, $batas) {
                $q->whereHas('kenaikanBerkalaAktif', function ($qq) use ($today, $batas) {
                    $qq->where('tanggal_berikutnya', '>=', $today);
                    if ($batas) $qq->where('tanggal_berikutnya', '<=', $batas);
                })->orWhereHas('kenaikanGolonganAktif');
            });
        }

        // ── Filter search nama / NIP ─────────────────────────────────────────
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_lengkap', 'like', "%{$s}%")
                  ->orWhere('nip', 'like', "%{$s}%");
            });
        }

        // ── Urutkan: tanggal berkala aktif terdekat tampil di atas ───────────
        $query->orderByRaw("
            COALESCE(
                (SELECT tanggal_berikutnya FROM kenaikan_berkalas
                 WHERE kenaikan_berkalas.id_karyawan = karyawans.id_karyawan
                   AND status IN ('scheduled','pending')
                 ORDER BY tanggal_berikutnya ASC LIMIT 1),
                '9999-12-31'
            ) ASC
        ");

        $karyawans = $query->paginate(15)->withQueryString();

        // ── Summary count ────────────────────────────────────────────────────
        $batas30 = $today->copy()->addDays(30);

        $totalBerkalaH30 = Karyawan::where('status_aktif', 'Aktif')
            ->whereHas('kenaikanBerkalaAktif', function ($q) use ($today, $batas30) {
                $q->whereBetween('tanggal_berikutnya', [$today, $batas30]);
            })->count();

        $totalGolonganPending = Karyawan::where('status_aktif', 'Aktif')
            ->whereHas('kenaikanGolonganAktif')
            ->count();

        $totalSemuaH30 = Karyawan::where('status_aktif', 'Aktif')
            ->where(function ($q) use ($today, $batas30) {
                $q->whereHas('kenaikanBerkalaAktif', function ($qq) use ($today, $batas30) {
                    $qq->whereBetween('tanggal_berikutnya', [$today, $batas30]);
                })->orWhereHas('kenaikanGolonganAktif');
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
    // Memanggil KenaikanBerkala::approve() yang sudah handle insert row berikutnya.

    public function approveBerkala(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'tanggal_berikutnya' => 'required|date',
            'catatan'            => 'nullable|string|max:500',
        ]);

        $berkala = $karyawan->kenaikanBerkalaAktif;

        if (! $berkala) {
            return redirect()->route('kenaikan.index', $request->only(['tipe', 'rentang', 'search']))
                ->with('error', "Tidak ada jadwal berkala aktif untuk {$karyawan->nama_lengkap}.");
        }

        DB::transaction(function () use ($request, $berkala) {
            // Approve: set status diterima + insert row scheduled berikutnya
            $next = $berkala->approve(Auth::user());

            // Override tanggal_berikutnya pada row baru jika admin set manual
            $next->update([
                'tanggal_berikutnya' => $request->tanggal_berikutnya,
                'status' => now()->gte($request->tanggal_berikutnya) ? 'pending' : 'scheduled',
            ]);

            if ($request->filled('catatan')) {
                $berkala->update(['catatan' => $request->catatan]);
            }
        });

        return redirect()->route('kenaikan.index', $request->only(['tipe', 'rentang', 'search']))
            ->with('success', "Kenaikan berkala {$karyawan->nama_lengkap} berhasil disetujui.");
    }

    // ── STOP BERKALA (sebelumnya "reject") ───────────────────────────────────
    // Jadwal ulang: update tanggal_berikutnya pada row aktif yang ada.

    public function rejectBerkala(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'tanggal_berikutnya' => 'required|date|after:today',
            'catatan'            => 'nullable|string|max:500',
        ]);

        $berkala = $karyawan->kenaikanBerkalaAktif;

        if (! $berkala) {
            return redirect()->route('kenaikan.index', $request->only(['tipe', 'rentang', 'search']))
                ->with('error', "Tidak ada jadwal berkala aktif untuk {$karyawan->nama_lengkap}.");
        }

        DB::transaction(function () use ($request, $berkala) {
            // Jadwal ulang: ubah tanggal, kembalikan ke scheduled
            $berkala->update([
                'tanggal_berikutnya' => $request->tanggal_berikutnya,
                'status'             => 'scheduled',
                'catatan'            => $request->catatan,
            ]);
        });

        return redirect()->route('kenaikan.index', $request->only(['tipe', 'rentang', 'search']))
            ->with('success', "Kenaikan berkala {$karyawan->nama_lengkap} dijadwal ulang.");
    }

    // ── APPROVE GOLONGAN ──────────────────────────────────────────────────────

    public function approveGolongan(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'golongan_baru_id'   => 'required|exists:golongans,id_golongan',
            'tanggal_efektif'    => 'required|date',
            'tanggal_berikutnya' => 'nullable|date|after:tanggal_efektif',
            'catatan'            => 'nullable|string|max:500',
        ]);

        $golonganAktif = $karyawan->kenaikanGolonganAktif;

        if (! $golonganAktif) {
            return redirect()->route('kenaikan.index', $request->only(['tipe', 'rentang', 'search']))
                ->with('error', "Tidak ada jadwal kenaikan golongan aktif untuk {$karyawan->nama_lengkap}.");
        }

        $golonganBaru = Golongan::findOrFail($request->golongan_baru_id);

        DB::transaction(function () use ($request, $karyawan, $golonganAktif, $golonganBaru) {
            // Override tanggal_berikutnya ke tanggal_efektif dari form sebelum approve
            $golonganAktif->update(['tanggal_berikutnya' => $request->tanggal_efektif]);

            // approve() → update status, update golongan karyawan, insert histori,
            // insert row scheduled berikutnya jika tanggal_berikutnya diisi
            $golonganAktif->approve(
                Auth::user(),
                $golonganBaru,
                $request->tanggal_berikutnya,
                $request->catatan,
            );
        });

        return redirect()->route('kenaikan.index', $request->only(['tipe', 'rentang', 'search']))
            ->with('success', "Kenaikan golongan {$karyawan->nama_lengkap} berhasil disetujui.");
    }

    // ── STOP GOLONGAN (sebelumnya "reject") ──────────────────────────────────

    public function rejectGolongan(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:500',
        ]);

        $golonganAktif = $karyawan->kenaikanGolonganAktif;

        if (! $golonganAktif) {
            return redirect()->route('kenaikan.index', $request->only(['tipe', 'rentang', 'search']))
                ->with('error', "Tidak ada jadwal kenaikan golongan aktif untuk {$karyawan->nama_lengkap}.");
        }

        DB::transaction(function () use ($request, $golonganAktif) {
            $golonganAktif->stop(Auth::user(), $request->catatan);
        });

        return redirect()->route('kenaikan.index', $request->only(['tipe', 'rentang', 'search']))
            ->with('success', "Pengajuan kenaikan golongan {$karyawan->nama_lengkap} dihentikan.");
    }
}
