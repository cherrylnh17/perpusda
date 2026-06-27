<?php

namespace App\Http\Controllers;

use App\Models\Pendidikan;
use Illuminate\Http\Request;

class PendidikanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pendidikan::withCount('karyawans');

        if ($request->filled('jenjang')) {
            $query->where('jenjang', $request->jenjang);
        }

        $pendidikans = $query->orderBy('jenjang')->paginate(10)->withQueryString();
        $jenjangList = Pendidikan::select('jenjang')->distinct()->orderBy('jenjang')->pluck('jenjang');

        return view('pendidikan.index', compact('pendidikans', 'jenjangList'));
    }

    public function create()
    {
        return view('pendidikan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenjang' => 'required|string|max:50',
        ], [
            'jenjang.required' => 'Jenjang wajib diisi.',
            'jenjang.max'      => 'Jenjang maksimal 50 karakter.',
        ]);

        Pendidikan::create([
            'jenjang' => $request->jenjang,
        ]);

        return redirect()->route('pendidikan.index')
            ->with('success', 'Pendidikan berhasil ditambahkan.');
    }

    public function edit(Pendidikan $pendidikan)
    {
        return view('pendidikan.edit', compact('pendidikan'));
    }

    public function update(Request $request, Pendidikan $pendidikan)
    {
        $request->validate([
            'jenjang' => 'required|string|max:50',
        ], [
            'jenjang.required' => 'Jenjang wajib diisi.',
            'jenjang.max'      => 'Jenjang maksimal 50 karakter.',
        ]);

        $pendidikan->update([
            'jenjang' => $request->jenjang,
        ]);

        return redirect()->route('pendidikan.index')
            ->with('success', 'Pendidikan berhasil diperbarui.');
    }

    public function destroy(Pendidikan $pendidikan)
    {
        if ($pendidikan->karyawans()->count() > 0) {
            return back()->with('error', 'Pendidikan tidak dapat dihapus karena masih digunakan oleh ' . $pendidikan->karyawans()->count() . ' karyawan.');
        }

        $pendidikan->delete();

        return redirect()->route('pendidikan.index')
            ->with('success', 'Pendidikan berhasil dihapus.');
    }

    // ── API: Ambil daftar pendidikan berdasarkan jenjang ─────────────
    public function getByJenjang(Request $request)
    {
        $jenjang = $request->query('jenjang');

        $pendidikans = Pendidikan::when($jenjang, function ($query) use ($jenjang) {
                $query->where('jenjang', $jenjang);
            })
            ->orderBy('jenjang')
            ->get(['id_pendidikan', 'jenjang']);

        return response()->json($pendidikans);
    }
}
