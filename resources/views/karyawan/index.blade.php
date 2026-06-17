<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Karyawan') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-5">

            {{-- ── Flash Messages ─────────────────────────────────────────── --}}
            @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-5 py-3 text-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-3 text-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
            @endif

            {{-- ── Banner Kenaikan Mendekati (H-30) ──────────────────────── --}}
            @if ($totalMendekatiKenaikan > 0 && !request()->filled('kenaikan'))
            <div class="flex items-center justify-between gap-4 px-5 py-3.5 bg-amber-50 border border-amber-200 rounded-xl">
                <div class="flex items-center gap-3">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        </svg>
                    </span>
                    <p class="text-sm text-amber-800 font-medium">
                        <span class="font-bold">{{ $totalMendekatiKenaikan }} karyawan</span>
                        memiliki jadwal/pengajuan kenaikan dalam 30 hari ke depan.
                    </p>
                </div>
                <a href="{{ route('karyawan.index', array_merge(request()->query(), ['kenaikan' => 'semua'])) }}"
                   class="flex-shrink-0 text-xs font-semibold text-amber-700 hover:text-amber-900 underline underline-offset-2">
                    Lihat Semua →
                </a>
            </div>
            @endif

            {{-- ── Toolbar: Search + Filter + Aksi ───────────────────────── --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <form method="GET" action="{{ route('karyawan.index') }}" id="filterForm">
                    <div class="flex flex-col lg:flex-row gap-3">

                        {{-- Search --}}
                        <div class="relative flex-1">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Cari nama, NIP, NIK..."
                                   class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                        </div>

                        {{-- Filter Status --}}
                        <select name="status" onchange="this.form.submit()"
                                class="text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none text-gray-600 bg-white">
                            <option value="">Semua Status</option>
                            @foreach (['Aktif','Pensiun'] as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>

                        {{-- Filter Jabatan --}}
                        <select name="jabatan" onchange="this.form.submit()"
                                class="text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none text-gray-600 bg-white">
                            <option value="">Semua Jabatan</option>
                            @foreach ($jabatans as $j)
                            <option value="{{ $j->id_jabatan }}" {{ request('jabatan') == $j->id_jabatan ? 'selected' : '' }}>
                                {{ $j->nama_jabatan }}
                            </option>
                            @endforeach
                        </select>

                        {{-- Filter Kontrak --}}
                        <select name="kontrak" onchange="this.form.submit()"
                                class="text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none text-gray-600 bg-white">
                            <option value="">Semua Kontrak</option>
                            @foreach ($kontraks as $k)
                            <option value="{{ $k->id_jenis_kontrak }}" {{ request('kontrak') == $k->id_jenis_kontrak ? 'selected' : '' }}>
                                {{ $k->nama_kontrak }}
                            </option>
                            @endforeach
                        </select>

                        {{-- Filter Golongan --}}
                        <select name="golongan" onchange="this.form.submit()"
                                class="text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none text-gray-600 bg-white">
                            <option value="">Semua Golongan</option>
                            @foreach ($golongans->groupBy('tipe') as $tipe => $items)
                                <optgroup label="{{ $tipe }}">
                                    @foreach ($items as $g)
                                    <option value="{{ $g->id_golongan }}" {{ request('golongan') == $g->id_golongan ? 'selected' : '' }}>
                                        {{ $g->nama_golongan }}
                                    </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>

                        {{-- Filter Kenaikan --}}
                        <select name="kenaikan" onchange="this.form.submit()"
                                class="text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-amber-500 outline-none text-gray-600 bg-white {{ request('kenaikan') ? 'border-amber-400 bg-amber-50 text-amber-700 font-semibold' : '' }}">
                            <option value="">Semua Kenaikan</option>
                            <option value="semua"    {{ request('kenaikan') == 'semua'    ? 'selected' : '' }}>⏰ H-30 Semua</option>
                            <option value="berkala"  {{ request('kenaikan') == 'berkala'  ? 'selected' : '' }}>🗓️ H-30 Berkala</option>
                            <option value="golongan" {{ request('kenaikan') == 'golongan' ? 'selected' : '' }}>📈 Pengajuan Golongan</option>
                        </select>

                        {{-- Tombol Search --}}
                        <button type="submit"
                                class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors">
                            Cari
                        </button>

                        @if (request()->hasAny(['search','status','jabatan','kontrak','golongan','kenaikan']))
                        <a href="{{ route('karyawan.index') }}"
                           class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-xl transition-colors text-center">
                            Reset
                        </a>
                        @endif
                    </div>
                </form>

                {{-- Row 2: Tombol Aksi --}}
                <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('karyawan.create') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Karyawan
                    </a>

                    {{-- Export/Import diaktifkan kembali sesuai kebutuhan --}}
                    {{-- <a href="{{ route('karyawan.export.excel', request()->query()) }}" ...> --}}
                    {{-- <a href="{{ route('karyawan.export.pdf',   request()->query()) }}" ...> --}}
                    {{-- <button onclick="..." > Import Excel </button> --}}
                    {{-- <a href="{{ route('karyawan.template') }}" ...> Template Import </a> --}}
                </div>
            </div>

            {{-- ── Tabel ───────────────────────────────────────────────────── --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                <div class="px-6 py-3 border-b border-gray-100 flex items-center justify-between text-xs text-gray-500">
                    <span>
                        Menampilkan <strong class="text-gray-700">{{ $karyawans->firstItem() ?? 0 }}–{{ $karyawans->lastItem() ?? 0 }}</strong>
                        dari <strong class="text-gray-700">{{ $karyawans->total() }}</strong> karyawan
                    </span>
                    @if (request('kenaikan'))
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        Filter Kenaikan Aktif
                    </span>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide border-b border-gray-100">
                            <tr>
                                <th class="px-5 py-3 text-left w-10">#</th>
                                <th class="px-5 py-3 text-left">Karyawan</th>
                                <th class="px-5 py-3 text-left">NIP / NIK</th>
                                <th class="px-5 py-3 text-left">Jabatan</th>
                                <th class="px-5 py-3 text-left">Kontrak</th>
                                <th class="px-5 py-3 text-left">Golongan</th>
                                <th class="px-5 py-3 text-left">Tgl Masuk</th>
                                <th class="px-5 py-3 text-center">Kenaikan</th>
                                <th class="px-5 py-3 text-center">Status</th>
                                <th class="px-5 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($karyawans as $k)
                            @php
                                $today   = now()->startOfDay();
                                $batas30 = now()->addDays(30)->startOfDay();

                                // ── Berkala: baca dari relasi kenaikanBerkalaAktif ──
                                $berkalaAktif = $k->kenaikanBerkalaAktif;
                                $berkalaDue   = $berkalaAktif?->tanggal_berikutnya;
                                $berkalaH30   = $berkalaDue
                                                && $berkalaDue->gte($today)
                                                && $berkalaDue->lte($batas30);
                                $berkalaSisa  = $berkalaDue
                                                ? $today->diffInDays($berkalaDue, false)
                                                : null;
                                $berkalaStatus = $berkalaAktif?->status; // scheduled | pending

                                // ── Golongan: baca dari relasi kenaikanGolonganAktif ──
                                $golonganAktif = $k->kenaikanGolonganAktif;
                            @endphp
                            <tr class="hover:bg-gray-50/60 transition-colors group {{ ($berkalaH30 || $berkalaAktif?->isPending() || $golonganAktif) ? 'bg-amber-50/30' : '' }}">

                                <td class="px-5 py-3 text-gray-400 text-xs">
                                    {{ $karyawans->firstItem() + $loop->index }}
                                </td>

                                {{-- Foto + Nama --}}
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        @if ($k->foto)
                                            <img src="{{ asset('storage/' . $k->foto) }}"
                                                 alt="{{ $k->nama_lengkap }}"
                                                 class="w-9 h-9 rounded-full object-cover ring-2 ring-gray-100 flex-shrink-0">
                                        @else
                                            <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 text-blue-600 font-bold text-sm">
                                                {{ strtoupper(substr($k->nama_lengkap, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-800 leading-tight">{{ $k->nama_lengkap }}</p>
                                            <p class="text-xs text-gray-400">{{ $k->agama ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- NIP / NIK --}}
                                <td class="px-5 py-3">
                                    <p class="font-mono text-xs text-gray-700">{{ $k->nip }}</p>
                                    <p class="font-mono text-xs text-gray-400">{{ $k->nik }}</p>
                                </td>

                                {{-- Jabatan --}}
                                <td class="px-5 py-3 text-gray-600">
                                    {{ $k->jabatan?->nama_jabatan ?? '-' }}
                                </td>

                                {{-- Kontrak --}}
                                <td class="px-5 py-3 text-gray-600 text-xs">
                                    {{ $k->jenisKontrak?->nama_kontrak ?? '-' }}
                                </td>

                                {{-- Golongan --}}
                                <td class="px-5 py-3 text-xs">
                                    @if ($k->golongan)
                                        <span class="font-medium text-gray-700">{{ $k->golongan->nama_golongan }}</span>
                                        <span class="block text-gray-400">{{ $k->golongan->tipe }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                {{-- Tgl Masuk --}}
                                <td class="px-5 py-3 text-gray-500 text-xs whitespace-nowrap">
                                    {{ $k->tanggal_masuk?->format('d M Y') }}
                                </td>

                                {{-- Kolom Kenaikan --}}
                                <td class="px-5 py-3">
                                    <div class="flex flex-col gap-1 items-center">

                                        {{-- Badge Kenaikan Berkala --}}
                                        @if ($berkalaAktif)
                                            @if ($berkalaAktif->isPending())
                                                {{-- Menunggu persetujuan --}}
                                                <span title="Kenaikan berkala menunggu persetujuan: {{ $berkalaDue?->format('d M Y') }}"
                                                      class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-700 whitespace-nowrap">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Berkala: Pending
                                                </span>
                                            @elseif ($berkalaH30)
                                                {{-- Scheduled dan H-30 --}}
                                                <span title="Kenaikan berkala: {{ $berkalaDue->format('d M Y') }}"
                                                      class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 whitespace-nowrap">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Berkala H-{{ $berkalaSisa }}
                                                </span>
                                            @else
                                                {{-- Scheduled, masih lama --}}
                                                <span class="text-xs text-gray-400 whitespace-nowrap">
                                                    🗓️ {{ $berkalaDue?->format('d M Y') }}
                                                </span>
                                            @endif
                                        @endif

                                        {{-- Badge Golongan Pending/Scheduled --}}
                                        @if ($golonganAktif)
                                            <span title="Jadwal kenaikan golongan: {{ $golonganAktif->tanggal_berikutnya?->format('d M Y') }}"
                                                  class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold
                                                         {{ $golonganAktif->isPending() ? 'bg-blue-100 text-blue-700' : 'bg-indigo-50 text-indigo-500' }}
                                                         whitespace-nowrap">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                </svg>
                                                {{ $golonganAktif->isPending() ? 'Golongan: Pending' : 'Golongan: Terjadwal' }}
                                            </span>
                                        @endif

                                        @if (!$berkalaAktif && !$golonganAktif)
                                            <span class="text-xs text-gray-300">—</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-3 text-center">
                                    @php
                                        $badge = match($k->status_aktif) {
                                            'Aktif'   => 'bg-green-100 text-green-700',
                                            'Pensiun' => 'bg-gray-100 text-gray-500',
                                            default   => 'bg-gray-100 text-gray-500',
                                        };
                                    @endphp
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badge }}">
                                        {{ $k->status_aktif }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-center gap-2 opacity-70 group-hover:opacity-100 transition-opacity">

                                        <a href="{{ route('karyawan.show', $k) }}"
                                           title="Detail"
                                           class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        <a href="{{ route('karyawan.edit', $k) }}"
                                           title="Edit"
                                           class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <form action="{{ route('karyawan.destroy', $k) }}" method="POST"
                                              onsubmit="return confirm('Hapus karyawan {{ addslashes($k->nama_lengkap) }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    title="Hapus"
                                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-red-500 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4a4 4 0 11-8 0 4 4 0 018 0zm6 4a2 2 0 100-4 2 2 0 000 4zM3 16a2 2 0 100-4 2 2 0 000 4z"/>
                                        </svg>
                                        <p class="text-sm">Tidak ada data karyawan.</p>
                                        <a href="{{ route('karyawan.create') }}" class="text-xs text-blue-600 hover:underline">Tambah karyawan pertama →</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($karyawans->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $karyawans->links() }}
                </div>
                @endif
            </div>

        </div>{{-- /max-w --}}
    </div>

    {{-- ══ Modal Import Excel ══════════════════════════════════════════════ --}}
    <div id="modalImport" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-semibold text-gray-800">Import Data Karyawan</h3>
                <button onclick="document.getElementById('modalImport').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route('karyawan.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center mb-4">
                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm text-gray-500 mb-2">Drag & drop file Excel, atau</p>
                    <label class="cursor-pointer text-sm text-blue-600 hover:underline font-medium">
                        Pilih File
                        <input type="file" name="file" accept=".xlsx,.xls" class="hidden"
                               onchange="document.getElementById('fileName').textContent = this.files[0]?.name ?? ''">
                    </label>
                    <p id="fileName" class="text-xs text-gray-400 mt-2"></p>
                    <p class="text-xs text-gray-400 mt-1">Format: .xlsx / .xls · Maks 5 MB</p>
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-xs text-amber-700 mb-4">
                    <p class="font-semibold mb-1">Kolom wajib:</p>
                    <p><strong>nip, nik, nama_lengkap, tgl_masuk</strong></p>
                    <p class="mt-1 text-amber-600">
                        Jadwal berkala: <strong>tgl_berkala_berikutnya</strong> (opsional, akan dibuat otomatis di tabel kenaikan).
                        Download template untuk format lengkap.
                    </p>
                </div>

                <div class="flex gap-3">
                    <button type="button"
                            onclick="document.getElementById('modalImport').classList.add('hidden')"
                            class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-xl transition-colors">
                        Upload & Import
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
