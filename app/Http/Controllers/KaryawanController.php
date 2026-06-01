<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Pendidikan;
use App\Models\JenisKontrak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\SimpleExcel\SimpleExcelWriter;

class KaryawanController extends Controller
{
    // ── INDEX ────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Karyawan::with(['jabatan', 'pendidikan', 'jenisKontrak']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_lengkap', 'like', "%{$s}%")
                  ->orWhere('nip', 'like', "%{$s}%")
                  ->orWhere('nik', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status'))  $query->where('status_aktif',    $request->status);
        if ($request->filled('jabatan')) $query->where('id_jabatan',       $request->jabatan);
        if ($request->filled('kontrak')) $query->where('id_jenis_kontrak', $request->kontrak);

        $karyawans = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        $jabatans  = Jabatan::orderBy('nama_jabatan')->get();
        $kontraks  = JenisKontrak::orderBy('nama_kontrak')->get();

        return view('karyawan.index', compact('karyawans', 'jabatans', 'kontraks'));
    }

    // ── CREATE ───────────────────────────────────────────────────────────────

    public function create()
    {
        $jabatans    = Jabatan::orderBy('nama_jabatan')->get();
        $pendidikans = Pendidikan::orderBy('nama_pendidikan')->get();
        $kontraks    = JenisKontrak::orderBy('nama_kontrak')->get();

        return view('karyawan.create', compact('jabatans', 'pendidikans', 'kontraks'));
    }

    // ── STORE ────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('karyawan/foto', 'public');
        }

        Karyawan::create($validated);

        return redirect()->route('karyawan.index')
            ->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    // ── SHOW ─────────────────────────────────────────────────────────────────

    public function show(Karyawan $karyawan)
    {
        $karyawan->load(['jabatan', 'pendidikan', 'jenisKontrak']);
        return view('karyawan.show', compact('karyawan'));
    }

    // ── EDIT ─────────────────────────────────────────────────────────────────

    public function edit(Karyawan $karyawan)
    {
        $jabatans    = Jabatan::orderBy('nama_jabatan')->get();
        $pendidikans = Pendidikan::orderBy('nama_pendidikan')->get();
        $kontraks    = JenisKontrak::orderBy('nama_kontrak')->get();

        return view('karyawan.edit', compact('karyawan', 'jabatans', 'pendidikans', 'kontraks'));
    }

    // ── UPDATE ───────────────────────────────────────────────────────────────

    public function update(Request $request, Karyawan $karyawan)
    {
        $validated = $request->validate($this->rules($karyawan->id_karyawan));

        if ($request->hasFile('foto')) {
            if ($karyawan->foto) Storage::disk('public')->delete($karyawan->foto);
            $validated['foto'] = $request->file('foto')->store('karyawan/foto', 'public');

        } elseif ($request->input('hapus_foto') === '1') {
            if ($karyawan->foto) Storage::disk('public')->delete($karyawan->foto);
            $validated['foto'] = null;

        } else {
            unset($validated['foto']);
        }

        $karyawan->update($validated);

        return redirect()->route('karyawan.index')
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    // ── DESTROY ──────────────────────────────────────────────────────────────

    public function destroy(Karyawan $karyawan)
    {
        if ($karyawan->foto) Storage::disk('public')->delete($karyawan->foto);
        $karyawan->delete();

        return redirect()->route('karyawan.index')
            ->with('success', 'Data karyawan berhasil dihapus.');
    }

    // ── EXPORT EXCEL ─────────────────────────────────────────────────────────

    public function exportExcel(Request $request)
    {
        $export = new \App\Exports\KaryawanExport($request->all());
        return $export->download('karyawan_' . now()->format('Ymd_His') . '.xlsx');
    }

    // ── IMPORT EXCEL ─────────────────────────────────────────────────────────

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120',
        ]);

        $import = new \App\Imports\KaryawanImport();
        $import->import($request->file('file'));

        $msg = "Import berhasil: {$import->imported} data ditambahkan/diperbarui.";
        if (!empty($import->errors)) {
            $msg .= ' Gagal: ' . implode('; ', array_slice($import->errors, 0, 3));
        }

        return redirect()->route('karyawan.index')->with('success', $msg);
    }

    // ── DOWNLOAD TEMPLATE ─────────────────────────────────────────────────────
    // Generate dinamis — tidak butuh file statis di public/

    public function downloadTemplate()
    {
        $filename = 'template_import_karyawan.xlsx';

        return response()->streamDownload(function () {

            $writer = SimpleExcelWriter::streamDownload('template_import_karyawan.xlsx');

            // Header kolom
            $writer->addHeader([
                'nip',
                'nik',
                'nama_lengkap',
                'jenis_kelamin',      // Laki-laki / Perempuan
                'tanggal_lahir',      // Format: dd/mm/yyyy
                'jabatan',            // Sesuai nama jabatan di sistem
                'pendidikan',         // Sesuai nama pendidikan di sistem
                'jenis_kontrak',      // Sesuai nama kontrak di sistem
                'tgl_masuk',          // Format: dd/mm/yyyy
                'tgl_mulai_jabatan',  // Format: dd/mm/yyyy
                'agama',
                'golongan_darah',     // A / B / AB / O
                'status',             // Aktif / Cuti / Pensiun / Resign
                'gaji',               // Angka saja, tanpa titik/koma (contoh: 5000000)
                'alamat',
            ]);

            // Baris contoh 1
            $writer->addRow([
                '198501012010011001',
                '3509012501850001',
                'Budi Santoso',
                'Laki-laki',
                '25/01/1985',
                'Staff IT',
                'S1',
                'Pegawai Tetap',
                '01/01/2010',
                '01/03/2010',
                'Islam',
                'O',
                'Aktif',
                '5000000',
                'Jl. Merdeka No. 1, Kudus',
            ]);

            // Baris contoh 2
            $writer->addRow([
                '199203152015012002',
                '3509031503920002',
                'Siti Rahayu',
                'Perempuan',
                '15/03/1992',
                'Staff Keuangan',
                'D3',
                'Pegawai Kontrak',
                '15/07/2015',
                '15/07/2015',
                'Islam',
                'A',
                'Aktif',
                '3500000',
                'Jl. Sudirman No. 45, Kudus',
            ]);

            $writer->close();

        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    // ── EXPORT PDF ───────────────────────────────────────────────────────────

    public function exportPdf(Request $request)
    {
        $query = Karyawan::with(['jabatan', 'pendidikan', 'jenisKontrak']);

        if ($request->filled('status'))  $query->where('status_aktif', $request->status);
        if ($request->filled('jabatan')) $query->where('id_jabatan',   $request->jabatan);

        $karyawans = $query->orderBy('nama_lengkap')->get();

        // Kirim filter aktif ke view supaya tampil di header PDF
        $filterInfo = [];
        if ($request->filled('status'))  $filterInfo[] = 'Status: ' . $request->status;
        if ($request->filled('jabatan')) {
            $j = Jabatan::find($request->jabatan);
            if ($j) $filterInfo[] = 'Jabatan: ' . $j->nama_jabatan;
        }

        $pdf = Pdf::loadView('karyawan.pdf', compact('karyawans', 'filterInfo'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('karyawan_' . now()->format('Ymd_His') . '.pdf');
    }

    // ── HAPUS FOTO ───────────────────────────────────────────────────────────

    public function deleteFoto(Karyawan $karyawan)
    {
        if ($karyawan->foto) {
            Storage::disk('public')->delete($karyawan->foto);
            $karyawan->update(['foto' => null]);
        }

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    // ── RULES ─────────────────────────────────────────────────────────────────

    private function rules(?int $ignoreId = null): array
    {
        return [
            'nama_lengkap'          => 'required|string|max:255',
            'nip'                   => ['required', 'string', 'max:50',
                                        Rule::unique('karyawans', 'nip')->ignore($ignoreId, 'id_karyawan')],
            'nik'                   => ['required', 'string', 'max:20',
                                        Rule::unique('karyawans', 'nik')->ignore($ignoreId, 'id_karyawan')],
            'jenis_kelamin'         => 'nullable|in:Laki-laki,Perempuan',
            'tanggal_lahir'         => 'nullable|date|before:today',
            'tanggal_masuk'         => 'required|date',
            'tanggal_mulai_jabatan' => 'required|date',
            'alamat'                => 'nullable|string',
            'agama'                 => 'nullable|string|max:50',
            'golongan_darah'        => 'nullable|string|max:2',
            'id_jabatan'            => 'nullable|exists:jabatans,id_jabatan',
            'id_pendidikan'         => 'nullable|exists:pendidikans,id_pendidikan',
            'id_jenis_kontrak'      => 'nullable|exists:jenis_kontraks,id_jenis_kontrak',
            'status_aktif'          => 'required|in:Aktif,Cuti,Pensiun,Resign',
            'gaji'                  => 'required|numeric|min:0',
            'foto'                  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }
}
