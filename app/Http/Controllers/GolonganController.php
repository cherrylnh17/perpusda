<?php

namespace App\Http\Controllers;

use App\Models\Golongan;
use Illuminate\Http\Request;

class GolonganController extends Controller
{
    public function index(Request $request)
    {
        $query = Golongan::withCount('karyawans');

        if ($request->filled('search')) {
            $query->where('nama_golongan', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        $golongans = $query->orderBy('tipe')->orderBy('nama_golongan')->paginate(10)->withQueryString();

        return view('golongan.index', compact('golongans'));
    }

    public function create()
    {
        return view('golongan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe'          => 'required|in:PNS,PPPK',
            'nama_golongan' => 'required|string|max:100|unique:golongans,nama_golongan,NULL,id_golongan,tipe,' . $request->tipe,
        ], [
            'tipe.required'          => 'Tipe golongan wajib dipilih.',
            'tipe.in'                => 'Tipe golongan tidak valid.',
            'nama_golongan.required' => 'Nama golongan wajib diisi.',
            'nama_golongan.unique'   => 'Golongan dengan tipe dan nama ini sudah terdaftar.',
            'nama_golongan.max'      => 'Nama golongan maksimal 100 karakter.',
        ]);

        Golongan::create([
            'tipe'          => $request->tipe,
            'nama_golongan' => $request->nama_golongan,
        ]);

        return redirect()->route('golongan.index')
            ->with('success', 'Golongan berhasil ditambahkan.');
    }

    public function edit(Golongan $golongan)
    {
        return view('golongan.edit', compact('golongan'));
    }

    public function update(Request $request, Golongan $golongan)
    {
        $request->validate([
            'tipe'          => 'required|in:PNS,PPPK',
            'nama_golongan' => 'required|string|max:100|unique:golongans,nama_golongan,' . $golongan->id_golongan . ',id_golongan,tipe,' . $request->tipe,
        ], [
            'tipe.required'          => 'Tipe golongan wajib dipilih.',
            'tipe.in'                => 'Tipe golongan tidak valid.',
            'nama_golongan.required' => 'Nama golongan wajib diisi.',
            'nama_golongan.unique'   => 'Golongan dengan tipe dan nama ini sudah terdaftar.',
            'nama_golongan.max'      => 'Nama golongan maksimal 100 karakter.',
        ]);

        $golongan->update([
            'tipe'          => $request->tipe,
            'nama_golongan' => $request->nama_golongan,
        ]);

        return redirect()->route('golongan.index')
            ->with('success', 'Golongan berhasil diperbarui.');
    }

    public function destroy(Golongan $golongan)
    {
        if ($golongan->karyawans()->count() > 0) {
            return back()->with('error', 'Golongan tidak dapat dihapus karena masih digunakan oleh ' . $golongan->karyawans()->count() . ' karyawan.');
        }

        $golongan->delete();

        return redirect()->route('golongan.index')
            ->with('success', 'Golongan berhasil dihapus.');
    }
}
