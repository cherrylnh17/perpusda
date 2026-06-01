<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    @endpush

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- BARIS 1 — Kartu Statistik Utama --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4 border border-gray-100">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4a4 4 0 11-8 0 4 4 0 018 0zm6 4a2 2 0 100-4 2 2 0 000 4zM3 16a2 2 0 100-4 2 2 0 000 4z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Karyawan</p>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($totalKaryawan) }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4 border border-gray-100">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Aktif</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($karyawanAktif) }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4 border border-gray-100">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-yellow-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Cuti</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ number_format($karyawanCuti) }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4 border border-gray-100">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pensiun / Resign</p>
                        <p class="text-2xl font-bold text-red-500">{{ number_format($karyawanPensiun + $karyawanResign) }}</p>
                    </div>
                </div>
            </div>

            {{-- BARIS 2 — Info Gaji + Master Data --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl shadow-sm p-5 text-white">
                    <p class="text-xs font-semibold uppercase tracking-widest text-blue-200">Total Gaji Aktif</p>
                    <p class="text-3xl font-extrabold mt-1">Rp {{ number_format($totalGaji, 0, ',', '.') }}</p>
                    <p class="text-xs text-blue-200 mt-2">Rata-rata: Rp {{ number_format($rataRataGaji, 0, ',', '.') }} / orang</p>
                </div>

                <div class="col-span-1 md:col-span-2 grid grid-cols-3 gap-4">
                    <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100 text-center">
                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalJabatan }}</p>
                        <p class="text-xs text-gray-500 mt-1">Jabatan</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100 text-center">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalPendidikan }}</p>
                        <p class="text-xs text-gray-500 mt-1">Pendidikan</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100 text-center">
                        <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalJenisKontrak }}</p>
                        <p class="text-xs text-gray-500 mt-1">Jenis Kontrak</p>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════
                 BARIS 3 — Demografi Gender (PENGGANTI chart bulan)
            ══════════════════════════════════════════════════════ --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- GRID LAKI-LAKI --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                                {{-- Ikon laki-laki --}}
                                <svg class="w-6 h-6 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M9 11.75A2.75 2.75 0 1 0 9 6.25a2.75 2.75 0 0 0 0 5.5zm0 1.5C6.58 13.25 2 14.46 2 17v1.5h14V17c0-2.54-4.58-3.75-7-3.75z"/>
                                    <path d="M15.5 2v2h2.09l-3.3 3.3A5.48 5.48 0 0 0 9 5.5C6.24 5.5 4 7.74 4 10.5S6.24 15.5 9 15.5s5-2.24 5-5c0-1.3-.48-2.49-1.27-3.4L16 3.83V6h2V2h-2.5z" opacity=".3"/>
                                    <path d="M19 2h-5v2h3.09l-3.57 3.57A4.965 4.965 0 0 0 9 6c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5c0-1.23-.45-2.35-1.18-3.22L16.17 4H19V2zM9 14c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-800">Laki-laki</h3>
                                <p class="text-xs text-gray-500">Distribusi usia</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-extrabold text-blue-600">{{ number_format($totalLaki) }}</p>
                            <p class="text-xs text-gray-400">karyawan</p>
                        </div>
                    </div>

                    {{-- Progress bar total vs keseluruhan --}}
                    @php $pctLaki = $totalKaryawan > 0 ? round($totalLaki / $totalKaryawan * 100) : 0; @endphp
                    <div class="mb-5">
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>Proporsi dari total karyawan</span>
                            <span class="font-semibold text-blue-600">{{ $pctLaki }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full transition-all" style="width: {{ $pctLaki }}%"></div>
                        </div>
                    </div>

                    {{-- Pie chart + legenda --}}
                    <div class="flex items-center gap-5">
                        <div class="relative flex-shrink-0" style="width:130px;height:130px">
                            <canvas id="chartLaki"></canvas>
                            {{-- Label tengah --}}
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <div class="text-center">
                                    <p class="text-lg font-bold text-gray-800">{{ $totalLaki }}</p>
                                    <p class="text-xs text-gray-400">total</p>
                                </div>
                            </div>
                        </div>

                        <ul class="flex-1 space-y-2">
                            @php
                                $umurLakiLabels  = ['20–30 thn', '31–40 thn', '41–50 thn', '50+ thn'];
                                $umurLakiColors  = ['#3b82f6', '#6366f1', '#8b5cf6', '#a5b4fc'];
                                $umurLakiValues  = array_values($umurLaki);
                            @endphp
                            @foreach ($umurLakiLabels as $i => $label)
                            @php $total_i = $umurLakiValues[$i]; @endphp
                            <li class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full flex-shrink-0"
                                      style="background:{{ $umurLakiColors[$i] }}"></span>
                                <span class="text-xs text-gray-600 flex-1">{{ $label }}</span>
                                <span class="text-xs font-bold text-gray-800">{{ $total_i }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Detail bar per kelompok --}}
                    <div class="mt-5 space-y-2">
                        @foreach ($umurLakiLabels as $i => $label)
                        @php
                            $val = $umurLakiValues[$i];
                            $pct = $totalLaki > 0 ? round($val / $totalLaki * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between text-xs text-gray-500 mb-0.5">
                                <span>{{ $label }}</span>
                                <span class="font-semibold" style="color:{{ $umurLakiColors[$i] }}">{{ $val }} orang ({{ $pct }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full" style="width:{{ $pct }}%;background:{{ $umurLakiColors[$i] }}"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- GRID PEREMPUAN --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-pink-100 flex items-center justify-center">
                                {{-- Ikon perempuan --}}
                                <svg class="w-6 h-6 text-pink-500" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2a5 5 0 1 0 0 10A5 5 0 0 0 12 2zm0 12c-5.33 0-8 2.67-8 4v2h16v-2c0-1.33-2.67-4-8-4z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-800">Perempuan</h3>
                                <p class="text-xs text-gray-500">Distribusi usia</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-extrabold text-pink-500">{{ number_format($totalPerempuan) }}</p>
                            <p class="text-xs text-gray-400">karyawan</p>
                        </div>
                    </div>

                    {{-- Progress bar total vs keseluruhan --}}
                    @php $pctPerempuan = $totalKaryawan > 0 ? round($totalPerempuan / $totalKaryawan * 100) : 0; @endphp
                    <div class="mb-5">
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>Proporsi dari total karyawan</span>
                            <span class="font-semibold text-pink-500">{{ $pctPerempuan }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-pink-400 h-2 rounded-full transition-all" style="width: {{ $pctPerempuan }}%"></div>
                        </div>
                    </div>

                    {{-- Pie chart + legenda --}}
                    <div class="flex items-center gap-5">
                        <div class="relative flex-shrink-0" style="width:130px;height:130px">
                            <canvas id="chartPerempuan"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <div class="text-center">
                                    <p class="text-lg font-bold text-gray-800">{{ $totalPerempuan }}</p>
                                    <p class="text-xs text-gray-400">total</p>
                                </div>
                            </div>
                        </div>

                        <ul class="flex-1 space-y-2">
                            @php
                                $umurPrLabels = ['20–30 thn', '31–40 thn', '41–50 thn', '50+ thn'];
                                $umurPrColors = ['#ec4899', '#f43f5e', '#fb7185', '#fda4af'];
                                $umurPrValues = array_values($umurPerempuan);
                            @endphp
                            @foreach ($umurPrLabels as $i => $label)
                            <li class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full flex-shrink-0"
                                      style="background:{{ $umurPrColors[$i] }}"></span>
                                <span class="text-xs text-gray-600 flex-1">{{ $label }}</span>
                                <span class="text-xs font-bold text-gray-800">{{ $umurPrValues[$i] }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Detail bar per kelompok --}}
                    <div class="mt-5 space-y-2">
                        @foreach ($umurPrLabels as $i => $label)
                        @php
                            $val = $umurPrValues[$i];
                            $pct = $totalPerempuan > 0 ? round($val / $totalPerempuan * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between text-xs text-gray-500 mb-0.5">
                                <span>{{ $label }}</span>
                                <span class="font-semibold" style="color:{{ $umurPrColors[$i] }}">{{ $val }} orang ({{ $pct }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full" style="width:{{ $pct }}%;background:{{ $umurPrColors[$i] }}"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- BARIS 4 — Chart Jabatan + Chart Pendidikan & Kontrak --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Distribusi per Jabatan</h3>
                    <div class="relative h-56">
                        <canvas id="chartJabatan"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 space-y-5">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3">Distribusi per Pendidikan</h3>
                        <div class="flex items-center gap-6">
                            <div class="relative w-36 h-36 flex-shrink-0">
                                <canvas id="chartPendidikan"></canvas>
                            </div>
                            <ul class="text-xs text-gray-600 space-y-1 flex-1">
                                @foreach ($karyawanPerPendidikan as $item)
                                <li class="flex justify-between">
                                    <span class="truncate">{{ $item->nama_pendidikan }}</span>
                                    <span class="font-semibold text-gray-800 ml-2">{{ $item->total }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3">Jenis Kontrak</h3>
                        <ul class="space-y-2">
                            @foreach ($karyawanPerKontrak as $item)
                            @php $pct = $totalKaryawan > 0 ? round($item->total / $totalKaryawan * 100) : 0; @endphp
                            <li>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-gray-600">{{ $item->nama_kontrak }}</span>
                                    <span class="font-semibold text-gray-800">{{ $item->total }} ({{ $pct }}%)</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                    <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            {{-- BARIS 5 — Tabel Karyawan Terbaru --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Karyawan Terbaru</h3>
                    <a href="{{ route('karyawan.index') }}" class="text-xs text-blue-600 hover:underline font-medium">Lihat Semua →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                            <tr>
                                <th class="px-6 py-3 text-left">Nama</th>
                                <th class="px-6 py-3 text-left">NIP</th>
                                <th class="px-6 py-3 text-left">Jabatan</th>
                                <th class="px-6 py-3 text-left">Kontrak</th>
                                <th class="px-6 py-3 text-left">Tgl Masuk</th>
                                <th class="px-6 py-3 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($karyawanTerbaru as $k)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3 font-medium text-gray-800">{{ $k->nama_lengkap }}</td>
                                <td class="px-6 py-3 text-gray-500 font-mono text-xs">{{ $k->nip }}</td>
                                <td class="px-6 py-3 text-gray-600">{{ $k->jabatan?->nama_jabatan ?? '-' }}</td>
                                <td class="px-6 py-3 text-gray-600">{{ $k->jenisKontrak?->nama_kontrak ?? '-' }}</td>
                                <td class="px-6 py-3 text-gray-500">{{ \Carbon\Carbon::parse($k->tanggal_masuk)->format('d M Y') }}</td>
                                <td class="px-6 py-3">
                                    @php
                                        $badge = match($k->status_aktif) {
                                            'Aktif'   => 'bg-green-100 text-green-700',
                                            'Cuti'    => 'bg-yellow-100 text-yellow-700',
                                            'Pensiun' => 'bg-gray-100 text-gray-600',
                                            'Resign'  => 'bg-red-100 text-red-600',
                                            default   => 'bg-gray-100 text-gray-600',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badge }}">{{ $k->status_aktif }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-6 text-center text-gray-400 text-sm">Belum ada data karyawan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {

        const palette = ['#3b82f6','#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#14b8a6','#f43f5e'];

        // ── Chart Jabatan (Bar horizontal) ──────────────────────────────────
        new Chart(document.getElementById('chartJabatan'), {
            type: 'bar',
            data: {
                labels: @json($karyawanPerJabatan->pluck('nama_jabatan')),
                datasets: [{
                    data: @json($karyawanPerJabatan->pluck('total')),
                    backgroundColor: palette,
                    borderRadius: 6,
                    borderSkipped: false,
                }],
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: '#f3f4f6' } },
                    y: { ticks: { font: { size: 11 } }, grid: { display: false } },
                },
            },
        });

        // ── Chart Pendidikan (Doughnut) ──────────────────────────────────────
        new Chart(document.getElementById('chartPendidikan'), {
            type: 'doughnut',
            data: {
                labels: @json($karyawanPerPendidikan->pluck('nama_pendidikan')),
                datasets: [{
                    data: @json($karyawanPerPendidikan->pluck('total')),
                    backgroundColor: palette,
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed}` } },
                },
            },
        });

        // ── Chart Laki-laki (Doughnut) ───────────────────────────────────────
        const lakiData   = @json(array_values($umurLaki));
        const lakiColors = ['#3b82f6','#6366f1','#8b5cf6','#a5b4fc'];
        const lakiLabels = ['20–30 thn','31–40 thn','41–50 thn','50+ thn'];

        new Chart(document.getElementById('chartLaki'), {
            type: 'doughnut',
            data: {
                labels: lakiLabels,
                datasets: [{
                    data: lakiData,
                    backgroundColor: lakiColors,
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed} orang` } },
                },
            },
        });

        // ── Chart Perempuan (Doughnut) ───────────────────────────────────────
        const prData   = @json(array_values($umurPerempuan));
        const prColors = ['#ec4899','#f43f5e','#fb7185','#fda4af'];
        const prLabels = ['20–30 thn','31–40 thn','41–50 thn','50+ thn'];

        new Chart(document.getElementById('chartPerempuan'), {
            type: 'doughnut',
            data: {
                labels: prLabels,
                datasets: [{
                    data: prData,
                    backgroundColor: prColors,
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed} orang` } },
                },
            },
        });

    });
    </script>
    @endpush

</x-app-layout>
