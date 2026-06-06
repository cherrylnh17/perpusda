<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ⏰ Jadwal Kenaikan Gaji & Jabatan
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

            {{-- ── Summary Cards ───────────────────────────────────────────── --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                {{-- Card: Semua H-30 --}}
                <a href="{{ route('kenaikan.index', ['tipe' => 'semua', 'rentang' => '30']) }}"
                   class="flex items-center gap-4 bg-white rounded-2xl border {{ $tipe === 'semua' && $rentang === '30' ? 'border-amber-400 ring-2 ring-amber-200' : 'border-gray-100' }} shadow-sm p-5 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalSemuaH30 }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Kenaikan dalam H-30</p>
                    </div>
                </a>

                {{-- Card: Gaji H-30 --}}
                <a href="{{ route('kenaikan.index', ['tipe' => 'gaji', 'rentang' => '30']) }}"
                   class="flex items-center gap-4 bg-white rounded-2xl border {{ $tipe === 'gaji' && $rentang === '30' ? 'border-green-400 ring-2 ring-green-200' : 'border-gray-100' }} shadow-sm p-5 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalGajiH30 }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Kenaikan Gaji H-30</p>
                    </div>
                </a>

                {{-- Card: Jabatan H-30 --}}
                <a href="{{ route('kenaikan.index', ['tipe' => 'jabatan', 'rentang' => '30']) }}"
                   class="flex items-center gap-4 bg-white rounded-2xl border {{ $tipe === 'jabatan' && $rentang === '30' ? 'border-blue-400 ring-2 ring-blue-200' : 'border-gray-100' }} shadow-sm p-5 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalJabatanH30 }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Kenaikan Jabatan H-30</p>
                    </div>
                </a>

            </div>

            {{-- ── Toolbar: Filter ─────────────────────────────────────────── --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <form method="GET" action="{{ route('kenaikan.index') }}">
                    <div class="flex flex-col lg:flex-row gap-3">

                        {{-- Search --}}
                        <div class="relative flex-1">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Cari nama atau NIP..."
                                   class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                        </div>

                        {{-- Filter Tipe --}}
                        <select name="tipe" onchange="this.form.submit()"
                                class="text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none text-gray-600 bg-white">
                            <option value="semua"   {{ $tipe === 'semua'   ? 'selected' : '' }}>⏰ Semua Kenaikan</option>
                            <option value="gaji"    {{ $tipe === 'gaji'    ? 'selected' : '' }}>💰 Kenaikan Gaji</option>
                            <option value="jabatan" {{ $tipe === 'jabatan' ? 'selected' : '' }}>📋 Kenaikan Jabatan</option>
                        </select>

                        {{-- Filter Rentang --}}
                        <select name="rentang" onchange="this.form.submit()"
                                class="text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none text-gray-600 bg-white">
                            <option value="7"     {{ $rentang === '7'     ? 'selected' : '' }}>H-7 (minggu ini)</option>
                            <option value="14"    {{ $rentang === '14'    ? 'selected' : '' }}>H-14 (2 minggu)</option>
                            <option value="30"    {{ $rentang === '30'    ? 'selected' : '' }}>H-30 (bulan ini)</option>
                            <option value="semua" {{ $rentang === 'semua' ? 'selected' : '' }}>Semua jadwal</option>
                        </select>

                        {{-- Tombol Cari --}}
                        <button type="submit"
                                class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors">
                            Cari
                        </button>

                        @if (request()->hasAny(['search', 'tipe', 'rentang']))
                        <a href="{{ route('kenaikan.index') }}"
                           class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-xl transition-colors text-center">
                            Reset
                        </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- ── Tabel Karyawan ───────────────────────────────────────────── --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                <div class="px-6 py-3 border-b border-gray-100 flex items-center justify-between text-xs text-gray-500">
                    <span>
                        Menampilkan <strong class="text-gray-700">{{ $karyawans->firstItem() ?? 0 }}–{{ $karyawans->lastItem() ?? 0 }}</strong>
                        dari <strong class="text-gray-700">{{ $karyawans->total() }}</strong> karyawan
                    </span>
                    <div class="flex items-center gap-3">
                        @if ($rentang !== 'semua')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            Filter H-{{ $rentang }} aktif
                        </span>
                        @endif
                        <span class="text-gray-400">Diurutkan: jadwal terdekat</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide border-b border-gray-100">
                            <tr>
                                <th class="px-5 py-3 text-left w-10">#</th>
                                <th class="px-5 py-3 text-left">Karyawan</th>
                                <th class="px-5 py-3 text-left">NIP</th>
                                <th class="px-5 py-3 text-left">Jabatan Sekarang</th>
                                <th class="px-5 py-3 text-right">Gaji Sekarang</th>
                                <th class="px-5 py-3 text-center">Kenaikan Gaji</th>
                                <th class="px-5 py-3 text-center">Kenaikan Jabatan</th>
                                <th class="px-5 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($karyawans as $k)
                            @php
                                $today      = now()->startOfDay();
                                $batas30    = now()->addDays(30)->startOfDay();
                                $batas7     = now()->addDays(7)->startOfDay();

                                $gajiDue    = $k->tanggal_kenaikan_gaji_berikutnya;
                                $jabatanDue = $k->tanggal_kenaikan_jabatan_berikutnya;

                                $gajiSisa    = $gajiDue    ? (int) $today->diffInDays($gajiDue, false) : null;
                                $jabatanSisa = $jabatanDue ? (int) $today->diffInDays($jabatanDue, false) : null;

                                $gajiH7  = $gajiSisa !== null && $gajiSisa >= 0 && $gajiSisa <= 7;
                                $gajiH30 = $gajiSisa !== null && $gajiSisa >= 0 && $gajiSisa <= 30;

                                $jabatanH7  = $jabatanSisa !== null && $jabatanSisa >= 0 && $jabatanSisa <= 7;
                                $jabatanH30 = $jabatanSisa !== null && $jabatanSisa >= 0 && $jabatanSisa <= 30;

                                // Warna baris: merah jika H-7, kuning jika H-30
                                $rowClass = '';
                                if (($gajiH7 || $jabatanH7)) {
                                    $rowClass = 'bg-red-50/40';
                                } elseif (($gajiH30 || $jabatanH30)) {
                                    $rowClass = 'bg-amber-50/30';
                                }

                                $hasPendingGaji    = $k->kenaikanGajiPending !== null;
                                $hasPendingJabatan = $k->kenaikanJabatanPending !== null;
                            @endphp
                            <tr class="hover:bg-gray-50/60 transition-colors group {{ $rowClass }}">

                                <td class="px-5 py-3 text-gray-400 text-xs">
                                    {{ $karyawans->firstItem() + $loop->index }}
                                </td>

                                {{-- Karyawan --}}
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
                                            <a href="{{ route('karyawan.show', $k) }}"
                                               class="font-medium text-gray-800 hover:text-blue-600 leading-tight">
                                                {{ $k->nama_lengkap }}
                                            </a>
                                            <p class="text-xs text-gray-400">{{ $k->golongan?->nama_golongan ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- NIP --}}
                                <td class="px-5 py-3 font-mono text-xs text-gray-600">
                                    {{ $k->nip }}
                                </td>

                                {{-- Jabatan --}}
                                <td class="px-5 py-3 text-gray-600 text-sm">
                                    {{ $k->jabatan?->nama_jabatan ?? '-' }}
                                </td>

                                {{-- Gaji --}}
                                <td class="px-5 py-3 text-right font-medium text-gray-700 text-xs whitespace-nowrap">
                                    Rp {{ number_format($k->gaji, 0, ',', '.') }}
                                </td>

                                {{-- Kenaikan Gaji --}}
                                <td class="px-5 py-3 text-center">
                                    @if ($gajiDue && $gajiSisa >= 0)
                                        @php
                                            if ($gajiH7) {
                                                $badgeClass = 'bg-red-100 text-red-700 border border-red-200';
                                                $ring = 'animate-pulse';
                                            } elseif ($gajiH30) {
                                                $badgeClass = 'bg-green-100 text-green-700 border border-green-200';
                                                $ring = '';
                                            } else {
                                                $badgeClass = 'bg-gray-100 text-gray-500';
                                                $ring = '';
                                            }
                                        @endphp
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold {{ $badgeClass }} {{ $ring }}">
                                                H-{{ $gajiSisa }}
                                            </span>
                                            <span class="text-xs text-gray-400">{{ $gajiDue->format('d M Y') }}</span>
                                            @if ($hasPendingGaji)
                                                <span class="text-xs text-amber-600 font-semibold">⏳ Pending</span>
                                            @endif
                                        </div>
                                    @elseif ($gajiDue && $gajiSisa < 0)
                                        <span class="text-xs text-red-400 font-medium">Terlambat diproses</span>
                                    @else
                                        <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </td>

                                {{-- Kenaikan Jabatan --}}
                                <td class="px-5 py-3 text-center">
                                    @if ($jabatanDue && $jabatanSisa >= 0)
                                        @php
                                            if ($jabatanH7) {
                                                $badgeJClass = 'bg-red-100 text-red-700 border border-red-200';
                                                $ringJ = 'animate-pulse';
                                            } elseif ($jabatanH30) {
                                                $badgeJClass = 'bg-blue-100 text-blue-700 border border-blue-200';
                                                $ringJ = '';
                                            } else {
                                                $badgeJClass = 'bg-gray-100 text-gray-500';
                                                $ringJ = '';
                                            }
                                        @endphp
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold {{ $badgeJClass }} {{ $ringJ }}">
                                                H-{{ $jabatanSisa }}
                                            </span>
                                            <span class="text-xs text-gray-400">{{ $jabatanDue->format('d M Y') }}</span>
                                            @if ($hasPendingJabatan)
                                                <span class="text-xs text-amber-600 font-semibold">⏳ Pending</span>
                                            @endif
                                        </div>
                                    @elseif ($jabatanDue && $jabatanSisa < 0)
                                        <span class="text-xs text-red-400 font-medium">Terlambat diproses</span>
                                    @else
                                        <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-center gap-2">

                                        {{-- Tombol Approve/Reject Gaji --}}
                                        @if ($gajiDue)
                                        <button
                                            type="button"
                                            title="Proses Kenaikan Gaji"
                                            onclick="openModalGaji({{ $k->id }}, '{{ addslashes($k->nama_lengkap) }}', {{ $k->gaji }}, '{{ $gajiDue?->format('Y-m-d') }}', {{ $hasPendingGaji ? 'true' : 'false' }})"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-green-600 hover:bg-green-700 text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Gaji
                                        </button>
                                        @endif

                                        {{-- Tombol Approve/Reject Jabatan --}}
                                        @if ($jabatanDue)
                                        <button
                                            type="button"
                                            title="Proses Kenaikan Jabatan"
                                            onclick="openModalJabatan({{ $k->id }}, '{{ addslashes($k->nama_lengkap) }}', '{{ addslashes($k->jabatan?->nama_jabatan ?? '') }}', '{{ $jabatanDue?->format('Y-m-d') }}', {{ $hasPendingJabatan ? 'true' : 'false' }})"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            Jabatan
                                        </button>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        <p class="text-sm">Tidak ada karyawan yang mendekati jadwal kenaikan.</p>
                                        <p class="text-xs text-gray-300">Coba ubah filter rentang hari atau tipe kenaikan.</p>
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


    {{-- ══════════════════════════════════════════════════════════════════════
         MODAL: Proses Kenaikan GAJI
    ══════════════════════════════════════════════════════════════════════ --}}
    <div id="modalGaji" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="font-semibold text-gray-800">Proses Kenaikan Gaji</h3>
                    <p id="modalGajiNama" class="text-xs text-gray-500 mt-0.5"></p>
                </div>
                <button onclick="closeModalGaji()"
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Info gaji sekarang --}}
            <div class="mx-6 mt-4 p-3 bg-gray-50 rounded-xl flex items-center justify-between text-sm">
                <span class="text-gray-500">Gaji Sekarang</span>
                <span id="modalGajiSekarang" class="font-semibold text-gray-800"></span>
            </div>

            {{-- Tab Approve / Reject --}}
            <div class="flex border-b border-gray-100 mx-6 mt-4 gap-0">
                <button id="tabApproveGaji" onclick="switchGajiTab('approve')"
                        class="flex-1 py-2.5 text-sm font-semibold text-center rounded-t-lg border-b-2 border-green-500 text-green-600 transition-colors">
                    ✅ Approve
                </button>
                <button id="tabRejectGaji" onclick="switchGajiTab('reject')"
                        class="flex-1 py-2.5 text-sm font-semibold text-center rounded-t-lg border-b-2 border-transparent text-gray-400 hover:text-gray-600 transition-colors">
                    ❌ Tolak & Jadwal Ulang
                </button>
            </div>

            {{-- Form Approve Gaji --}}
            <form id="formApproveGaji" method="POST" action="" class="px-6 py-4 space-y-4">
                @csrf
                @method('POST')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Gaji Baru <span class="text-red-500">*</span></label>
                        <input type="number" name="gaji_baru" id="inputGajiBaru" min="0" step="1000" required
                               class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none"
                               placeholder="Nominal gaji baru">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Tanggal Berlaku <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_berlaku" id="inputGajiTglBerlaku" required
                               class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Jadwal Kenaikan Berikutnya <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_berikutnya" required
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Catatan</label>
                    <textarea name="catatan" rows="2" maxlength="500"
                              placeholder="Opsional..."
                              class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none resize-none"></textarea>
                </div>

                <div class="flex gap-3 pt-1">
                    <button type="button" onclick="closeModalGaji()"
                            class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl transition-colors">
                        Approve Kenaikan
                    </button>
                </div>
            </form>

            {{-- Form Reject Gaji --}}
            <form id="formRejectGaji" method="POST" action="" class="px-6 py-4 space-y-4 hidden">
                @csrf
                @method('POST')

                <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-700">
                    Kenaikan ditolak — gaji tidak berubah. Anda hanya menjadwal ulang tanggal kenaikan berikutnya.
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Jadwal Kenaikan Berikutnya <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_berikutnya" required
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-400 focus:border-transparent outline-none">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Alasan Penolakan</label>
                    <textarea name="catatan" rows="2" maxlength="500"
                              placeholder="Jelaskan alasan penolakan..."
                              class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-400 focus:border-transparent outline-none resize-none"></textarea>
                </div>

                <div class="flex gap-3 pt-1">
                    <button type="button" onclick="closeModalGaji()"
                            class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-colors">
                        Tolak & Jadwal Ulang
                    </button>
                </div>
            </form>

        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════════════════
         MODAL: Proses Kenaikan JABATAN
    ══════════════════════════════════════════════════════════════════════ --}}
    <div id="modalJabatan" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="font-semibold text-gray-800">Proses Kenaikan Jabatan</h3>
                    <p id="modalJabatanNama" class="text-xs text-gray-500 mt-0.5"></p>
                </div>
                <button onclick="closeModalJabatan()"
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Info jabatan sekarang --}}
            <div class="mx-6 mt-4 p-3 bg-gray-50 rounded-xl flex items-center justify-between text-sm">
                <span class="text-gray-500">Jabatan Sekarang</span>
                <span id="modalJabatanSekarang" class="font-semibold text-gray-800"></span>
            </div>

            {{-- Tab Approve / Reject --}}
            <div class="flex border-b border-gray-100 mx-6 mt-4 gap-0">
                <button id="tabApproveJabatan" onclick="switchJabatanTab('approve')"
                        class="flex-1 py-2.5 text-sm font-semibold text-center rounded-t-lg border-b-2 border-blue-500 text-blue-600 transition-colors">
                    ✅ Approve
                </button>
                <button id="tabRejectJabatan" onclick="switchJabatanTab('reject')"
                        class="flex-1 py-2.5 text-sm font-semibold text-center rounded-t-lg border-b-2 border-transparent text-gray-400 hover:text-gray-600 transition-colors">
                    ❌ Tolak & Jadwal Ulang
                </button>
            </div>

            {{-- Form Approve Jabatan --}}
            <form id="formApproveJabatan" method="POST" action="" class="px-6 py-4 space-y-4">
                @csrf
                @method('POST')

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Jabatan Baru <span class="text-red-500">*</span></label>
                    <select name="jabatan_baru_id" required
                            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none bg-white">
                        <option value="">-- Pilih jabatan baru --</option>
                        @foreach ($jabatans as $jab)
                        <option value="{{ $jab->id }}">{{ $jab->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Tanggal Berlaku <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_berlaku" id="inputJabatanTglBerlaku" required
                               class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Jadwal Berikutnya <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_berikutnya" required
                               class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Catatan</label>
                    <textarea name="catatan" rows="2" maxlength="500"
                              placeholder="Opsional..."
                              class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none resize-none"></textarea>
                </div>

                <div class="flex gap-3 pt-1">
                    <button type="button" onclick="closeModalJabatan()"
                            class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors">
                        Approve Kenaikan
                    </button>
                </div>
            </form>

            {{-- Form Reject Jabatan --}}
            <form id="formRejectJabatan" method="POST" action="" class="px-6 py-4 space-y-4 hidden">
                @csrf
                @method('POST')

                <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-700">
                    Kenaikan ditolak — jabatan tidak berubah. Anda hanya menjadwal ulang tanggal kenaikan berikutnya.
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Jadwal Kenaikan Berikutnya <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_berikutnya" required
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-400 focus:border-transparent outline-none">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Alasan Penolakan</label>
                    <textarea name="catatan" rows="2" maxlength="500"
                              placeholder="Jelaskan alasan penolakan..."
                              class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-400 focus:border-transparent outline-none resize-none"></textarea>
                </div>

                <div class="flex gap-3 pt-1">
                    <button type="button" onclick="closeModalJabatan()"
                            class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-colors">
                        Tolak & Jadwal Ulang
                    </button>
                </div>
            </form>

        </div>
    </div>


    {{-- ══ JavaScript ══════════════════════════════════════════════════════ --}}
    <script>
    // ── Routes (dikirim dari PHP ke JS) ──────────────────────────────────────
    const routeApproveGaji    = '{{ rtrim(url('/kenaikan'), '/') }}/__ID__/approve-gaji';
    const routeRejectGaji     = '{{ rtrim(url('/kenaikan'), '/') }}/__ID__/reject-gaji';
    const routeApproveJabatan = '{{ rtrim(url('/kenaikan'), '/') }}/__ID__/approve-jabatan';
    const routeRejectJabatan  = '{{ rtrim(url('/kenaikan'), '/') }}/__ID__/reject-jabatan';

    function makeUrl(template, id) {
        return template.replace('__ID__', id);
    }

    function formatRupiah(angka) {
        return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
    }

    // ── Modal Gaji ────────────────────────────────────────────────────────────
    let currentGajiKaryawanId = null;

    function openModalGaji(id, nama, gajiSekarang, tglKenaikan, hasPending) {
        currentGajiKaryawanId = id;
        document.getElementById('modalGajiNama').textContent = nama;
        document.getElementById('modalGajiSekarang').textContent = formatRupiah(gajiSekarang);
        document.getElementById('inputGajiBaru').value = gajiSekarang;
        document.getElementById('inputGajiTglBerlaku').value = tglKenaikan;

        // Set action URLs
        document.getElementById('formApproveGaji').action = makeUrl(routeApproveGaji, id);
        document.getElementById('formRejectGaji').action  = makeUrl(routeRejectGaji, id);

        switchGajiTab('approve');
        document.getElementById('modalGaji').classList.remove('hidden');
    }

    function closeModalGaji() {
        document.getElementById('modalGaji').classList.add('hidden');
    }

    function switchGajiTab(tab) {
        const approve = document.getElementById('formApproveGaji');
        const reject  = document.getElementById('formRejectGaji');
        const tabA    = document.getElementById('tabApproveGaji');
        const tabR    = document.getElementById('tabRejectGaji');

        if (tab === 'approve') {
            approve.classList.remove('hidden');
            reject.classList.add('hidden');
            tabA.classList.add('border-green-500', 'text-green-600');
            tabA.classList.remove('border-transparent', 'text-gray-400');
            tabR.classList.add('border-transparent', 'text-gray-400');
            tabR.classList.remove('border-red-500', 'text-red-600');
        } else {
            reject.classList.remove('hidden');
            approve.classList.add('hidden');
            tabR.classList.add('border-red-500', 'text-red-600');
            tabR.classList.remove('border-transparent', 'text-gray-400');
            tabA.classList.add('border-transparent', 'text-gray-400');
            tabA.classList.remove('border-green-500', 'text-green-600');
        }
    }

    // ── Modal Jabatan ─────────────────────────────────────────────────────────
    let currentJabatanKaryawanId = null;

    function openModalJabatan(id, nama, jabatanSekarang, tglKenaikan, hasPending) {
        currentJabatanKaryawanId = id;
        document.getElementById('modalJabatanNama').textContent = nama;
        document.getElementById('modalJabatanSekarang').textContent = jabatanSekarang || '-';
        document.getElementById('inputJabatanTglBerlaku').value = tglKenaikan;

        // Set action URLs
        document.getElementById('formApproveJabatan').action = makeUrl(routeApproveJabatan, id);
        document.getElementById('formRejectJabatan').action  = makeUrl(routeRejectJabatan, id);

        switchJabatanTab('approve');
        document.getElementById('modalJabatan').classList.remove('hidden');
    }

    function closeModalJabatan() {
        document.getElementById('modalJabatan').classList.add('hidden');
    }

    function switchJabatanTab(tab) {
        const approve = document.getElementById('formApproveJabatan');
        const reject  = document.getElementById('formRejectJabatan');
        const tabA    = document.getElementById('tabApproveJabatan');
        const tabR    = document.getElementById('tabRejectJabatan');

        if (tab === 'approve') {
            approve.classList.remove('hidden');
            reject.classList.add('hidden');
            tabA.classList.add('border-blue-500', 'text-blue-600');
            tabA.classList.remove('border-transparent', 'text-gray-400');
            tabR.classList.add('border-transparent', 'text-gray-400');
            tabR.classList.remove('border-red-500', 'text-red-600');
        } else {
            reject.classList.remove('hidden');
            approve.classList.add('hidden');
            tabR.classList.add('border-red-500', 'text-red-600');
            tabR.classList.remove('border-transparent', 'text-gray-400');
            tabA.classList.add('border-transparent', 'text-gray-400');
            tabA.classList.remove('border-blue-500', 'text-blue-600');
        }
    }

    // ── Tutup modal jika klik backdrop ───────────────────────────────────────
    document.getElementById('modalGaji').addEventListener('click', function (e) {
        if (e.target === this) closeModalGaji();
    });
    document.getElementById('modalJabatan').addEventListener('click', function (e) {
        if (e.target === this) closeModalJabatan();
    });
    </script>

</x-app-layout>
