<?php

namespace App\Http\Controllers;

use App\Models\Pendidikan;
use Illuminate\Http\Request;

class PendidikanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pendidikan::withCount('karyawans');

        if ($request->filled('search')) {
            $query->where('nama_pendidikan', 'like', '%' . $request->search . '%');
        }

        $pendidikans = $query->orderBy('nama_pendidikan')->paginate(10)->withQueryString();

        return view('pendidikan.index', compact('pendidikans'));
    }

    public function create()
    {
        return view('pendidikan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pendidikan' => 'required|string|max:100|unique:pendidikans,nama_pendidikan',
        ], [
            'nama_pendidikan.required' => 'Nama pendidikan wajib diisi.',
            'nama_pendidikan.unique'   => 'Pendidikan ini sudah terdaftar.',
            'nama_pendidikan.max'      => 'Nama pendidikan maksimal 100 karakter.',
        ]);

        Pendidikan::create(['nama_pendidikan' => $request->nama_pendidikan]);

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
            'nama_pendidikan' => 'required|string|max:100|unique:pendidikans,nama_pendidikan,' . $pendidikan->id_pendidikan . ',id_pendidikan',
        ], [
            'nama_pendidikan.required' => 'Nama pendidikan wajib diisi.',
            'nama_pendidikan.unique'   => 'Pendidikan ini sudah terdaftar.',
            'nama_pendidikan.max'      => 'Nama pendidikan maksimal 100 karakter.',
        ]);

        $pendidikan->update(['nama_pendidikan' => $request->nama_pendidikan]);

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
}
