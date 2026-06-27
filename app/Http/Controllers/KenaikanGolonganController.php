<?php

namespace App\Http\Controllers;

use App\Models\Golongan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KenaikanGolonganController extends Controller
{
    // ── INDEX: daftar karyawan dengan pengajuan kenaikan golongan ─────────────

    public function index(Request $request)
    {
        $today = Carbon::today();

        $query = Karyawan::with([
            'jabatan',
            'golongan',
            'jenisKontrak',
            'kenaikanGolonganAktif.golonganBaru',
        ])->where('status_aktif', 'Aktif');

        // Hanya yang punya kenaikan golongan aktif
        $query->whereHas('kenaikanGolonganAktif');

        // ── Filter search nama / NIP ──────────────────────────────────────────
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_lengkap', 'like', "%{$s}%")
                  ->orWhere('nip', 'like', "%{$s}%");
            });
        }

        $query->orderBy('nama_lengkap');

        $karyawans = $query->paginate(15)->withQueryString();

        // ── Summary count ─────────────────────────────────────────────────────
        $totalGolonganPending = Karyawan::where('status_aktif', 'Aktif')
            ->whereHas('kenaikanGolonganAktif')
            ->count();

        // ── Daftar golongan untuk modal approve ───────────────────────────────
        $golongans = Golongan::orderBy('tipe')->orderBy('nama_golongan')->get();

        return view('kenaikan-golongan.index', compact(
            'karyawans',
            'golongans',
            'totalGolonganPending',
        ));
    }

    // ── APPROVE GOLONGAN ─────────────────────────────────────────────────────

    public function approve(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'golongan_baru_id'   => 'required|exists:golongans,id_golongan',
            'tanggal_efektif'    => 'required|date',
            'tanggal_berikutnya' => 'nullable|date|after:tanggal_efektif',
            'catatan'            => 'nullable|string|max:500',
        ]);

        $golonganAktif = $karyawan->kenaikanGolonganAktif;

        if (! $golonganAktif) {
            return redirect()->route('kenaikan-golongan.index', $request->only(['search']))
                ->with('error', "Tidak ada jadwal kenaikan golongan aktif untuk {$karyawan->nama_lengkap}.");
        }

        $golonganBaru = Golongan::findOrFail($request->golongan_baru_id);

        DB::transaction(function () use ($request, $golonganAktif, $golonganBaru) {
            $golonganAktif->update(['tanggal_berikutnya' => $request->tanggal_efektif]);

            $golonganAktif->approve(
                Auth::user(),
                $golonganBaru,
                $request->tanggal_berikutnya,
                $request->catatan,
            );
        });

        return redirect()->route('kenaikan-golongan.index', $request->only(['search']))
            ->with('success', "Kenaikan golongan {$karyawan->nama_lengkap} berhasil disetujui.");
    }

    // ── REJECT GOLONGAN ──────────────────────────────────────────────────────

    public function reject(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:500',
        ]);

        $golonganAktif = $karyawan->kenaikanGolonganAktif;

        if (! $golonganAktif) {
            return redirect()->route('kenaikan-golongan.index', $request->only(['search']))
                ->with('error', "Tidak ada jadwal kenaikan golongan aktif untuk {$karyawan->nama_lengkap}.");
        }

        DB::transaction(function () use ($request, $golonganAktif) {
            $golonganAktif->stop(Auth::user(), $request->catatan);
        });

        return redirect()->route('kenaikan-golongan.index', $request->only(['search']))
            ->with('success', "Pengajuan kenaikan golongan {$karyawan->nama_lengkap} dihentikan.");
    }
}
