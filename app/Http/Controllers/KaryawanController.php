<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Pendidikan;
use App\Models\JenisKontrak;
use App\Models\Golongan;
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
        $query = Karyawan::with(['jabatan', 'pendidikan', 'jenisKontrak', 'golongan']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_lengkap', 'like', "%{$s}%")
                  ->orWhere('nip', 'like', "%{$s}%")
                  ->orWhere('nik', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status'))   $query->where('status_aktif',    $request->status);
        if ($request->filled('jabatan'))  $query->where('jabatan_id',       $request->jabatan);
        if ($request->filled('kontrak'))  $query->where('jenis_kontrak_id', $request->kontrak);
        if ($request->filled('golongan')) $query->where('golongan_id',      $request->golongan);

        // Filter karyawan yang mendekati kenaikan (H-30) untuk badge di index
        if ($request->filled('kenaikan')) {
            $batas = now()->addDays(30)->toDateString();
            $today = now()->toDateString();
            if ($request->kenaikan === 'gaji') {
                $query->whereBetween('tanggal_kenaikan_gaji_berikutnya', [$today, $batas]);
            } elseif ($request->kenaikan === 'jabatan') {
                $query->whereBetween('tanggal_kenaikan_jabatan_berikutnya', [$today, $batas]);
            } elseif ($request->kenaikan === 'semua') {
                $query->where(function ($q) use ($today, $batas) {
                    $q->whereBetween('tanggal_kenaikan_gaji_berikutnya', [$today, $batas])
                      ->orWhereBetween('tanggal_kenaikan_jabatan_berikutnya', [$today, $batas]);
                });
            }
        }

        $karyawans = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        $jabatans  = Jabatan::orderBy('nama_jabatan')->get();
        $kontraks  = JenisKontrak::orderBy('nama_kontrak')->get();
        $golongans = Golongan::orderBy('tipe')->orderBy('nama_golongan')->get();

        // Hitung total karyawan yang mendekati kenaikan (untuk badge notif di toolbar)
        $batas = now()->addDays(30)->toDateString();
        $today = now()->toDateString();
        $totalMendekatiKenaikan = Karyawan::where('status_aktif', 'Aktif')
            ->where(function ($q) use ($today, $batas) {
                $q->whereBetween('tanggal_kenaikan_gaji_berikutnya', [$today, $batas])
                  ->orWhereBetween('tanggal_kenaikan_jabatan_berikutnya', [$today, $batas]);
            })->count();

        return view('karyawan.index', compact(
            'karyawans', 'jabatans', 'kontraks', 'golongans', 'totalMendekatiKenaikan'
        ));
    }

    // ── CREATE ───────────────────────────────────────────────────────────────

    public function create()
    {
        $jabatans    = Jabatan::orderBy('nama_jabatan')->get();
        $pendidikans = Pendidikan::orderBy('nama_pendidikan')->get();
        $kontraks    = JenisKontrak::orderBy('nama_kontrak')->get();
        $golongans   = Golongan::orderBy('tipe')->orderBy('nama_golongan')->get();

        return view('karyawan.create', compact('jabatans', 'pendidikans', 'kontraks', 'golongans'));
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
        $karyawan->load([
            'jabatan',
            'pendidikan',
            'jenisKontrak',
            'golongan',
            'kenaikanGajis'   => fn ($q) => $q->orderByDesc('tanggal_berlaku')->limit(5),
            'kenaikanJabatans' => fn ($q) => $q->with(['jabatanLama', 'jabatanBaru'])
                                               ->orderByDesc('tanggal_berlaku')->limit(5),
        ]);

        return view('karyawan.show', compact('karyawan'));
    }

    // ── EDIT ─────────────────────────────────────────────────────────────────

    public function edit(Karyawan $karyawan)
    {
        $jabatans    = Jabatan::orderBy('nama_jabatan')->get();
        $pendidikans = Pendidikan::orderBy('nama_pendidikan')->get();
        $kontraks    = JenisKontrak::orderBy('nama_kontrak')->get();
        $golongans   = Golongan::orderBy('tipe')->orderBy('nama_golongan')->get();

        return view('karyawan.edit', compact('karyawan', 'jabatans', 'pendidikans', 'kontraks', 'golongans'));
    }

    // ── UPDATE ───────────────────────────────────────────────────────────────

    public function update(Request $request, Karyawan $karyawan)
    {
        $validated = $request->validate($this->rules($karyawan->id));

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

        return redirect()->route('karyawan.show', $karyawan)
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

    public function downloadTemplate()
    {
        $filename = 'template_import_karyawan.xlsx';

        return response()->streamDownload(function () {

            $writer = SimpleExcelWriter::streamDownload('template_import_karyawan.xlsx');

            $writer->addHeader([
                'nip', 'nik', 'nama_lengkap', 'jenis_kelamin',
                'tanggal_lahir', 'jabatan', 'pendidikan', 'jenis_kontrak',
                'golongan',
                'tgl_masuk', 'tgl_mulai_jabatan', 'agama',
                'golongan_darah', 'status', 'gaji', 'alamat',
                'tgl_kenaikan_gaji',    // Tanggal kenaikan gaji berikutnya (d/m/Y)
                'tgl_kenaikan_jabatan', // Tanggal kenaikan jabatan berikutnya (d/m/Y)
            ]);

            $writer->addRow([
                '198501012010011001', '3509012501850001', 'Budi Santoso', 'Laki-laki',
                '25/01/1985', 'Staff IT', 'S1', 'Pegawai Tetap',
                'III/a',
                '01/01/2010', '01/03/2010', 'Islam',
                'O', 'Aktif', '5000000', 'Jl. Merdeka No. 1, Kudus',
                '01/01/2026', '01/03/2026',
            ]);

            $writer->close();

        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    // ── EXPORT PDF ───────────────────────────────────────────────────────────

    public function exportPdf(Request $request)
    {
        $query = Karyawan::with(['jabatan', 'pendidikan', 'jenisKontrak', 'golongan']);

        if ($request->filled('status'))   $query->where('status_aktif', $request->status);
        if ($request->filled('jabatan'))  $query->where('jabatan_id',   $request->jabatan);
        if ($request->filled('golongan')) $query->where('golongan_id',  $request->golongan);

        $karyawans = $query->orderBy('nama_lengkap')->get();

        $filterInfo = [];
        if ($request->filled('status'))   $filterInfo[] = 'Status: ' . $request->status;
        if ($request->filled('jabatan')) {
            $j = Jabatan::find($request->jabatan);
            if ($j) $filterInfo[] = 'Jabatan: ' . $j->nama_jabatan;
        }
        if ($request->filled('golongan')) {
            $g = Golongan::find($request->golongan);
            if ($g) $filterInfo[] = 'Golongan: ' . $g->nama_golongan . ' (' . $g->tipe . ')';
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
            'nama_lengkap'                       => 'required|string|max:255',
            'nip'                                => ['required', 'string', 'max:50',
                                                    Rule::unique('karyawans', 'nip')->ignore($ignoreId)],
            'nik'                                => ['required', 'string', 'max:20',
                                                    Rule::unique('karyawans', 'nik')->ignore($ignoreId)],
            'jenis_kelamin'                      => 'nullable|in:Laki-laki,Perempuan',
            'tanggal_lahir'                      => 'nullable|date|before:today',
            'tanggal_masuk'                      => 'required|date',
            'tanggal_mulai_jabatan'              => 'required|date',
            'alamat'                             => 'nullable|string',
            'agama'                              => 'nullable|string|max:50',
            'golongan_darah'                     => 'nullable|string|max:2',
            'jabatan_id'                         => 'nullable|exists:jabatans,id',
            'pendidikan_id'                      => 'nullable|exists:pendidikans,id',
            'jenis_kontrak_id'                   => 'nullable|exists:jenis_kontraks,id',
            'golongan_id'                        => 'nullable|exists:golongans,id',
            'status_aktif'                       => 'required|in:Aktif,Cuti,Pensiun,Resign',
            'gaji'                               => 'required|numeric|min:0',
            'foto'                               => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            // Kolom baru
            'tanggal_kenaikan_gaji_berikutnya'   => 'nullable|date',
            'tanggal_kenaikan_jabatan_berikutnya' => 'nullable|date',
        ];
    }
}
