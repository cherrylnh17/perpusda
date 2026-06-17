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
        $query = Karyawan::with([
            'jabatan', 'pendidikan', 'jenisKontrak', 'golongan',
            'kenaikanBerkalaAktif',
            'kenaikanGolonganAktif.golonganBaru',
        ]);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_lengkap', 'like', "%{$s}%")
                  ->orWhere('nip', 'like', "%{$s}%")
                  ->orWhere('nik', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status'))   $query->where('status_aktif',     $request->status);
        if ($request->filled('jabatan'))  $query->where('id_jabatan',       $request->jabatan);
        if ($request->filled('kontrak'))  $query->where('id_jenis_kontrak', $request->kontrak);
        if ($request->filled('golongan')) $query->where('id_golongan',      $request->golongan);

        // Filter karyawan yang mendekati/menunggu kenaikan (untuk badge di index)
        if ($request->filled('kenaikan')) {
            $batas = now()->addDays(30)->toDateString();
            $today = now()->toDateString();

            if ($request->kenaikan === 'berkala') {
                $query->whereHas('kenaikanBerkalaAktif', function ($q) use ($today, $batas) {
                    $q->whereBetween('tanggal_berikutnya', [$today, $batas]);
                });
            } elseif ($request->kenaikan === 'golongan') {
                $query->whereHas('kenaikanGolonganAktif');
            } elseif ($request->kenaikan === 'semua') {
                $query->where(function ($q) use ($today, $batas) {
                    $q->whereHas('kenaikanBerkalaAktif', function ($qq) use ($today, $batas) {
                        $qq->whereBetween('tanggal_berikutnya', [$today, $batas]);
                    })->orWhereHas('kenaikanGolonganAktif');
                });
            }
        }

        $karyawans = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        $jabatans  = Jabatan::orderBy('nama_jabatan')->get();
        $kontraks  = JenisKontrak::orderBy('nama_kontrak')->get();
        $golongans = Golongan::orderBy('tipe')->orderBy('nama_golongan')->get();

        // Hitung total karyawan yang mendekati/menunggu kenaikan (badge notif di toolbar)
        $batas = now()->addDays(30)->toDateString();
        $today = now()->toDateString();
        $totalMendekatiKenaikan = Karyawan::where('status_aktif', 'Aktif')
            ->where(function ($q) use ($today, $batas) {
                $q->whereHas('kenaikanBerkalaAktif', function ($qq) use ($today, $batas) {
                    $qq->whereBetween('tanggal_berikutnya', [$today, $batas]);
                })->orWhereHas('kenaikanGolonganAktif');
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

        // 'tanggal_berkala_berikutnya' bukan kolom di tabel karyawans — dipakai
        // untuk membuat jadwal kenaikan berkala pertama di tabel kenaikan_berkalas.
        $tanggalBerkalaBerikutnya  = $validated['tanggal_berkala_berikutnya']  ?? null;
        $tanggalGolonganBerikutnya = $validated['tanggal_golongan_berikutnya'] ?? null;
        unset($validated['tanggal_berkala_berikutnya'], $validated['tanggal_golongan_berikutnya']);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('karyawan/foto', 'public');
        }

        $karyawan = Karyawan::create($validated);

        if ($tanggalBerkalaBerikutnya) {
            $karyawan->kenaikanBerkalas()->create([
                'tanggal_berikutnya' => $tanggalBerkalaBerikutnya,
                'status'             => now()->gte($tanggalBerkalaBerikutnya) ? 'pending' : 'scheduled',
            ]);
        }

        if ($tanggalGolonganBerikutnya) {
            $karyawan->kenaikanGolongans()->create([
                'tanggal_berikutnya' => $tanggalGolonganBerikutnya,
                'golongan_lama_id'   => $karyawan->id_golongan, // snapshot golongan saat input
                'golongan_baru_id'   => null,                   // diisi admin saat approve
                'status'             => now()->gte($tanggalGolonganBerikutnya) ? 'pending' : 'scheduled',
            ]);
        }

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
            'kenaikanBerkalaAktif',
            'kenaikanGolonganAktif' => fn ($q) => $q->with(['golonganLama', 'golonganBaru']),
            'kenaikanBerkalas'   => fn ($q) => $q->orderByDesc('tanggal_berikutnya')
                                                  ->with('diprosesByUser')
                                                  ->limit(5),
            'historiGolongans'   => fn ($q) => $q->with(['golonganLama', 'golonganBaru', 'dicatatByUser'])
                                                  ->limit(5),
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
        $validated = $request->validate($this->rules($karyawan->id_karyawan));

        // Ambil jadwal sebelum di-unset dari $validated
        $tanggalBerkalaBerikutnya  = $validated['tanggal_berkala_berikutnya']  ?? null;
        $tanggalGolonganBerikutnya = $validated['tanggal_golongan_berikutnya'] ?? null;
        unset($validated['tanggal_berkala_berikutnya'], $validated['tanggal_golongan_berikutnya']);

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

        // ── Upsert jadwal berkala ─────────────────────────────────────────────
        // Cari row scheduled/pending yang sudah ada; update jika ada, buat jika tidak,
        // atau hapus jadwal jika field dikosongkan.
        $berkalaAktif = $karyawan->kenaikanBerkalaAktif;

        if ($tanggalBerkalaBerikutnya) {
            if ($berkalaAktif) {
                $berkalaAktif->update([
                    'tanggal_berikutnya' => $tanggalBerkalaBerikutnya,
                    'status' => now()->gte($tanggalBerkalaBerikutnya) ? 'pending' : 'scheduled',
                ]);
            } else {
                $karyawan->kenaikanBerkalas()->create([
                    'tanggal_berikutnya' => $tanggalBerkalaBerikutnya,
                    'status' => now()->gte($tanggalBerkalaBerikutnya) ? 'pending' : 'scheduled',
                ]);
            }
        } elseif ($berkalaAktif) {
            // Field dikosongkan → hapus jadwal yang ada
            $berkalaAktif->delete();
        }

        // ── Upsert jadwal golongan ────────────────────────────────────────────
        $karyawan->refresh(); // pastikan id_golongan sudah ter-update
        $golonganAktif = $karyawan->kenaikanGolonganAktif;

        if ($tanggalGolonganBerikutnya) {
            if ($golonganAktif) {
                $golonganAktif->update([
                    'tanggal_berikutnya' => $tanggalGolonganBerikutnya,
                    'status' => now()->gte($tanggalGolonganBerikutnya) ? 'pending' : 'scheduled',
                ]);
            } else {
                $karyawan->kenaikanGolongans()->create([
                    'tanggal_berikutnya' => $tanggalGolonganBerikutnya,
                    'golongan_lama_id'   => $karyawan->id_golongan, // snapshot saat input
                    'golongan_baru_id'   => null,
                    'status' => now()->gte($tanggalGolonganBerikutnya) ? 'pending' : 'scheduled',
                ]);
            }
        } elseif ($golonganAktif) {
            // Field dikosongkan → hapus jadwal yang ada
            $golonganAktif->delete();
        }

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
            'nama_lengkap'           => 'required|string|max:255',
            'nip'                    => ['required', 'string', 'max:50',
                                          Rule::unique('karyawans', 'nip')->ignore($ignoreId, 'id_karyawan')],
            'nik'                    => ['required', 'string', 'max:20',
                                          Rule::unique('karyawans', 'nik')->ignore($ignoreId, 'id_karyawan')],
            'jenis_kelamin'          => 'nullable|in:Laki-laki,Perempuan',
            'tanggal_lahir'          => 'nullable|date|before:today',
            'tanggal_masuk'          => 'required|date',
            'alamat'                 => 'nullable|string',
            'agama'                  => 'nullable|string|max:50',
            'golongan_darah'         => 'nullable|string|max:2',
            'id_jabatan'             => 'nullable|exists:jabatans,id_jabatan',
            'id_pendidikan'          => 'nullable|exists:pendidikans,id_pendidikan',
            'id_jenis_kontrak'       => 'nullable|exists:jenis_kontraks,id_jenis_kontrak',
            'id_golongan'            => 'nullable|exists:golongans,id_golongan|required_with:tanggal_golongan_berikutnya',
            'status_aktif'           => 'required|in:Aktif,Pensiun',
            'foto'                   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            // Jadwal kenaikan berkala awal (hanya dipakai di store(), lihat catatan di sana)
            'tanggal_berkala_berikutnya'  => 'nullable|date',
            // Jadwal kenaikan golongan awal — id_golongan wajib diisi jika field ini diisi
            'tanggal_golongan_berikutnya' => 'nullable|date',
        ];
    }
}
