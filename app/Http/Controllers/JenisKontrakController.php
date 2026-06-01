<?php

namespace App\Http\Controllers;

use App\Models\JenisKontrak;
use Illuminate\Http\Request;

class JenisKontrakController extends Controller
{
    public function index(Request $request)
    {
        $query = JenisKontrak::withCount('karyawans');

        if ($request->filled('search')) {
            $query->where('nama_kontrak', 'like', '%' . $request->search . '%');
        }

        $kontraks = $query->orderBy('nama_kontrak')->paginate(10)->withQueryString();

        return view('kontrak.index', compact('kontraks'));
    }

    public function create()
    {
        return view('kontrak.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kontrak'     => 'required|string|max:100|unique:jenis_kontraks,nama_kontrak',
            'jam_kerja_sehari' => 'required|integer|min:1|max:24',
        ], [
            'nama_kontrak.required'     => 'Nama kontrak wajib diisi.',
            'nama_kontrak.unique'       => 'Jenis kontrak ini sudah terdaftar.',
            'nama_kontrak.max'          => 'Nama kontrak maksimal 100 karakter.',
            'jam_kerja_sehari.required' => 'Jam kerja per hari wajib diisi.',
            'jam_kerja_sehari.integer'  => 'Jam kerja harus berupa angka.',
            'jam_kerja_sehari.min'      => 'Jam kerja minimal 1 jam.',
            'jam_kerja_sehari.max'      => 'Jam kerja maksimal 24 jam.',
        ]);

        JenisKontrak::create($request->only('nama_kontrak', 'jam_kerja_sehari'));

        return redirect()->route('kontrak.index')
            ->with('success', 'Jenis kontrak berhasil ditambahkan.');
    }

    public function edit(JenisKontrak $jenis_kontrak)
    {
        return view('kontrak.edit', compact('jenis_kontrak'));
    }

    public function update(Request $request, JenisKontrak $jenis_kontrak)
    {
        $request->validate([
            'nama_kontrak'     => 'required|string|max:100|unique:jenis_kontraks,nama_kontrak,' . $jenis_kontrak->id_jenis_kontrak . ',id_jenis_kontrak',
            'jam_kerja_sehari' => 'required|integer|min:1|max:24',
        ], [
            'nama_kontrak.required'     => 'Nama kontrak wajib diisi.',
            'nama_kontrak.unique'       => 'Jenis kontrak ini sudah terdaftar.',
            'nama_kontrak.max'          => 'Nama kontrak maksimal 100 karakter.',
            'jam_kerja_sehari.required' => 'Jam kerja per hari wajib diisi.',
            'jam_kerja_sehari.integer'  => 'Jam kerja harus berupa angka.',
            'jam_kerja_sehari.min'      => 'Jam kerja minimal 1 jam.',
            'jam_kerja_sehari.max'      => 'Jam kerja maksimal 24 jam.',
        ]);

        $jenis_kontrak->update($request->only('nama_kontrak', 'jam_kerja_sehari'));

        return redirect()->route('kontrak.index')
            ->with('success', 'Jenis kontrak berhasil diperbarui.');
    }

    public function destroy(JenisKontrak $jenis_kontrak)
    {
        if ($jenis_kontrak->karyawans()->count() > 0) {
            return back()->with('error', 'Jenis kontrak tidak dapat dihapus karena masih digunakan oleh ' . $jenis_kontrak->karyawans()->count() . ' karyawan.');
        }

        $jenis_kontrak->delete();

        return redirect()->route('kontrak.index')
            ->with('success', 'Jenis kontrak berhasil dihapus.');
    }
}
