<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KenaikanBerkalaController extends Controller
{
    // ── INDEX: daftar karyawan dengan countdown kenaikan berkala ──────────────

    public function index(Request $request)
    {
        $rentang = $request->input('rentang', '30'); // 7 | 14 | 30 | semua
        $today   = Carbon::today();

        $query = Karyawan::with([
            'jabatan',
            'golongan',
            'jenisKontrak',
            'kenaikanBerkalaAktif',
        ])->where('status_aktif', 'Aktif');

        // ── Filter rentang ─────────────────────────────────────────────────────
        $batas = $rentang !== 'semua'
            ? $today->copy()->addDays((int) $rentang)
            : null;

        $query->whereHas('kenaikanBerkalaAktif', function ($q) use ($today, $batas) {
            $q->where('tanggal_berikutnya', '>=', $today);
            if ($batas) $q->where('tanggal_berikutnya', '<=', $batas);
        });

        // ── Filter search nama / NIP ──────────────────────────────────────────
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_lengkap', 'like', "%{$s}%")
                  ->orWhere('nip', 'like', "%{$s}%");
            });
        }

        // ── Urutkan: tanggal berkala aktif terdekat ───────────────────────────
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

        // ── Summary count ─────────────────────────────────────────────────────
        $batas30 = $today->copy()->addDays(30);

        $totalBerkalaH30 = Karyawan::where('status_aktif', 'Aktif')
            ->whereHas('kenaikanBerkalaAktif', function ($q) use ($today, $batas30) {
                $q->whereBetween('tanggal_berikutnya', [$today, $batas30]);
            })->count();

        return view('kenaikan-berkala.index', compact(
            'karyawans',
            'totalBerkalaH30',
            'rentang',
        ));
    }

    // ── APPROVE BERKALA ──────────────────────────────────────────────────────

    public function approve(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'tanggal_berikutnya' => 'required|date',
            'catatan'            => 'nullable|string|max:500',
        ]);

        $berkala = $karyawan->kenaikanBerkalaAktif;

        if (! $berkala) {
            return redirect()->route('kenaikan-berkala.index', $request->only(['rentang', 'search']))
                ->with('error', "Tidak ada jadwal berkala aktif untuk {$karyawan->nama_lengkap}.");
        }

        DB::transaction(function () use ($request, $berkala) {
            $next = $berkala->approve(Auth::user());

            $next->update([
                'tanggal_berikutnya' => $request->tanggal_berikutnya,
                'status' => now()->gte($request->tanggal_berikutnya) ? 'pending' : 'scheduled',
            ]);

            if ($request->filled('catatan')) {
                $berkala->update(['catatan' => $request->catatan]);
            }
        });

        return redirect()->route('kenaikan-berkala.index', $request->only(['rentang', 'search']))
            ->with('success', "Kenaikan berkala {$karyawan->nama_lengkap} berhasil disetujui.");
    }

    // ── REJECT BERKALA (Jadwal Ulang) ────────────────────────────────────────

    public function reject(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'tanggal_berikutnya' => 'required|date|after:today',
            'catatan'            => 'nullable|string|max:500',
        ]);

        $berkala = $karyawan->kenaikanBerkalaAktif;

        if (! $berkala) {
            return redirect()->route('kenaikan-berkala.index', $request->only(['rentang', 'search']))
                ->with('error', "Tidak ada jadwal berkala aktif untuk {$karyawan->nama_lengkap}.");
        }

        DB::transaction(function () use ($request, $berkala) {
            $berkala->update([
                'tanggal_berikutnya' => $request->tanggal_berikutnya,
                'status'             => 'scheduled',
                'catatan'            => $request->catatan,
            ]);
        });

        return redirect()->route('kenaikan-berkala.index', $request->only(['rentang', 'search']))
            ->with('success', "Kenaikan berkala {$karyawan->nama_lengkap} dijadwal ulang.");
    }
}