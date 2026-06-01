<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Jabatan::withCount('karyawans');

        if ($request->filled('search')) {
            $query->where('nama_jabatan', 'like', '%' . $request->search . '%');
        }

        $jabatans = $query->orderBy('nama_jabatan')->paginate(10)->withQueryString();

        return view('jabatan.index', compact('jabatans'));
    }

    public function create()
    {
        return view('jabatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:150|unique:jabatans,nama_jabatan',
        ], [
            'nama_jabatan.required' => 'Nama jabatan wajib diisi.',
            'nama_jabatan.unique'   => 'Jabatan ini sudah terdaftar.',
            'nama_jabatan.max'      => 'Nama jabatan maksimal 150 karakter.',
        ]);

        Jabatan::create(['nama_jabatan' => $request->nama_jabatan]);

        return redirect()->route('jabatan.index')
            ->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function edit(Jabatan $jabatan)
    {
        return view('jabatan.edit', compact('jabatan'));
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:150|unique:jabatans,nama_jabatan,' . $jabatan->id_jabatan . ',id_jabatan',
        ], [
            'nama_jabatan.required' => 'Nama jabatan wajib diisi.',
            'nama_jabatan.unique'   => 'Jabatan ini sudah terdaftar.',
            'nama_jabatan.max'      => 'Nama jabatan maksimal 150 karakter.',
        ]);

        $jabatan->update(['nama_jabatan' => $request->nama_jabatan]);

        return redirect()->route('jabatan.index')
            ->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan)
    {
        if ($jabatan->karyawans()->count() > 0) {
            return back()->with('error', 'Jabatan tidak dapat dihapus karena masih digunakan oleh ' . $jabatan->karyawans()->count() . ' karyawan.');
        }

        $jabatan->delete();

        return redirect()->route('jabatan.index')
            ->with('success', 'Jabatan berhasil dihapus.');
    }
}
