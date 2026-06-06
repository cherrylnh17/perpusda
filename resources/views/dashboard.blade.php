<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
            <span class="text-xs text-gray-400">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</span>
        </div>
    </x-slot>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    @endpush

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-5">

            {{-- ══════════════════════════════════════════════════════
                 BARIS 1 — Total Karyawan + PNS/PPPK per Gender
            ══════════════════════════════════════════════════════ --}}
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">

                {{-- Total --}}
                <div class="col-span-2 lg:col-span-1 bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-5 text-white shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-widest text-blue-200 mb-1">Total Karyawan</p>
                    <p class="text-4xl font-extrabold">{{ number_format($totalKaryawan) }}</p>
                    <p class="text-xs text-blue-200 mt-2">
                        <span class="text-white font-semibold">{{ $totalLaki }}L</span> /
                        <span class="text-white font-semibold">{{ $totalPerempuan }}P</span>
                    </p>
                </div>

                {{-- PNS Laki-laki --}}
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="mb-3">
                        <span class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="7" r="4"/><path stroke-linecap="round" stroke-linejoin="round" d="M5.5 21a8.38 8.38 0 0113 0"/>
                            </svg>
                        </span>
                    </div>
                    <p class="text-2xl font-extrabold text-blue-600">{{ number_format($pnsLaki) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">PNS Laki-laki</p>
                </div>

                {{-- PNS Perempuan --}}
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="mb-3">
                        <span class="w-8 h-8 rounded-lg bg-pink-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="7" r="4"/><path stroke-linecap="round" stroke-linejoin="round" d="M5.5 21a8.38 8.38 0 0113 0"/>
                            </svg>
                        </span>
                    </div>
                    <p class="text-2xl font-extrabold text-pink-500">{{ number_format($pnsPerempuan) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">PNS Perempuan</p>
                </div>

                {{-- PPPK Laki-laki --}}
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="mb-3">
                        <span class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="7" r="4"/><path stroke-linecap="round" stroke-linejoin="round" d="M5.5 21a8.38 8.38 0 0113 0"/>
                            </svg>
                        </span>
                    </div>
                    <p class="text-2xl font-extrabold text-violet-600">{{ number_format($pppkLaki) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">PPPK Laki-laki</p>
                </div>

                {{-- PPPK Perempuan --}}
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="mb-3">
                        <span class="w-8 h-8 rounded-lg bg-fuchsia-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-fuchsia-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="7" r="4"/><path stroke-linecap="round" stroke-linejoin="round" d="M5.5 21a8.38 8.38 0 0113 0"/>
                            </svg>
                        </span>
                    </div>
                    <p class="text-2xl font-extrabold text-fuchsia-500">{{ number_format($pppkPerempuan) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">PPPK Perempuan</p>
                </div>

            </div>

            {{-- ══════════════════════════════════════════════════════
                 BARIS 2 — Kenaikan Gaji & Jabatan H-30
            ══════════════════════════════════════════════════════ --}}
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

    {{-- ── Kenaikan Gaji ──────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">Kenaikan Gaji</h3>
                    <p class="text-xs text-gray-400">Jadwal dalam 30 hari ke depan</p>
                </div>
            </div>

            <a href="{{ route('kenaikan.index', ['tipe' => 'gaji', 'rentang' => '30']) }}"
               class="inline-flex items-center gap-1 text-xs font-semibold text-green-600 hover:text-green-800 transition-colors">
                Lihat Semua
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        {{-- List --}}
        @forelse ($karyawanNaikGaji as $k)
        @php
            $sisa = (int) now()->startOfDay()->diffInDays($k->tanggal_kenaikan_gaji_berikutnya, false);
            if ($sisa <= 7) {
                $pill = 'bg-red-100 text-red-700';
                $pulse = 'animate-pulse';
            } elseif ($sisa <= 14) {
                $pill = 'bg-orange-100 text-orange-700';
                $pulse = '';
            } else {
                $pill = 'bg-green-100 text-green-700';
                $pulse = '';
            }
        @endphp
        <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-gray-50/60 transition-colors {{ !$loop->last ? 'border-b border-gray-50' : '' }}">

            {{-- Avatar --}}
            @if ($k->foto)
                <img src="{{ asset('storage/' . $k->foto) }}"
                     alt="{{ $k->nama_lengkap }}"
                     class="w-9 h-9 rounded-full object-cover ring-2 ring-gray-100 flex-shrink-0">
            @else
                <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr($k->nama_lengkap, 0, 1)) }}
                </div>
            @endif

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800 truncate">{{ $k->nama_lengkap }}</p>
                <p class="text-xs text-gray-400 truncate">{{ $k->jabatan?->nama_jabatan ?? '-' }}</p>
            </div>

            {{-- Countdown + tanggal --}}
            <div class="text-right flex-shrink-0">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $pill }} {{ $pulse }}">
                    H-{{ $sisa }}
                </span>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ $k->tanggal_kenaikan_gaji_berikutnya->format('d M Y') }}
                </p>
            </div>
        </div>
        @empty
        <div class="px-6 py-10 text-center">
            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-xs text-gray-400">Tidak ada jadwal kenaikan gaji dalam 30 hari ke depan.</p>
        </div>
        @endforelse

        {{-- Footer link --}}
        @if ($karyawanNaikGaji->isNotEmpty())
        <div class="px-6 py-3 border-t border-gray-50 bg-gray-50/50">
            <a href="{{ route('kenaikan.index', ['tipe' => 'gaji', 'rentang' => '30']) }}"
               class="flex items-center justify-center gap-1.5 text-xs font-medium text-green-600 hover:text-green-800 transition-colors">
                Lihat semua jadwal kenaikan gaji
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        @endif

    </div>{{-- /Kenaikan Gaji --}}


    {{-- ── Kenaikan Jabatan ────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">Kenaikan Jabatan</h3>
                    <p class="text-xs text-gray-400">Jadwal dalam 30 hari ke depan</p>
                </div>
            </div>

            <a href="{{ route('kenaikan.index', ['tipe' => 'jabatan', 'rentang' => '30']) }}"
               class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                Lihat Semua
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        {{-- List --}}
        @forelse ($karyawanNaikJabatan as $k)
        @php
            $sisaJ = (int) now()->startOfDay()->diffInDays($k->tanggal_kenaikan_jabatan_berikutnya, false);
            if ($sisaJ <= 7) {
                $pillJ = 'bg-red-100 text-red-700';
                $pulseJ = 'animate-pulse';
            } elseif ($sisaJ <= 14) {
                $pillJ = 'bg-orange-100 text-orange-700';
                $pulseJ = '';
            } else {
                $pillJ = 'bg-blue-100 text-blue-700';
                $pulseJ = '';
            }
        @endphp
        <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-gray-50/60 transition-colors {{ !$loop->last ? 'border-b border-gray-50' : '' }}">

            {{-- Avatar --}}
            @if ($k->foto)
                <img src="{{ asset('storage/' . $k->foto) }}"
                     alt="{{ $k->nama_lengkap }}"
                     class="w-9 h-9 rounded-full object-cover ring-2 ring-gray-100 flex-shrink-0">
            @else
                <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr($k->nama_lengkap, 0, 1)) }}
                </div>
            @endif

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800 truncate">{{ $k->nama_lengkap }}</p>
                <p class="text-xs text-gray-400 truncate">{{ $k->jabatan?->nama_jabatan ?? '-' }}</p>
            </div>

            {{-- Countdown + tanggal --}}
            <div class="text-right flex-shrink-0">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $pillJ }} {{ $pulseJ }}">
                    H-{{ $sisaJ }}
                </span>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ $k->tanggal_kenaikan_jabatan_berikutnya->format('d M Y') }}
                </p>
            </div>
        </div>
        @empty
        <div class="px-6 py-10 text-center">
            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <p class="text-xs text-gray-400">Tidak ada jadwal kenaikan jabatan dalam 30 hari ke depan.</p>
        </div>
        @endforelse

        {{-- Footer link --}}
        @if ($karyawanNaikJabatan->isNotEmpty())
        <div class="px-6 py-3 border-t border-gray-50 bg-gray-50/50">
            <a href="{{ route('kenaikan.index', ['tipe' => 'jabatan', 'rentang' => '30']) }}"
               class="flex items-center justify-center gap-1.5 text-xs font-medium text-blue-600 hover:text-blue-800 transition-colors">
                Lihat semua jadwal kenaikan jabatan
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        @endif

    </div>{{-- /Kenaikan Jabatan --}}

</div>

            {{-- ══════════════════════════════════════════════════════
                 BARIS 3 — Demografi Gender per Jenis Kontrak
            ══════════════════════════════════════════════════════ --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- LAKI-LAKI --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="7" r="4"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 21a8.38 8.38 0 0113 0"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-800">Laki-laki</h3>
                                <p class="text-xs text-gray-400">Distribusi per jenis kontrak</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-extrabold text-blue-600">{{ number_format($totalLaki) }}</p>
                            <p class="text-xs text-gray-400">karyawan</p>
                        </div>
                    </div>

                    @php $pctLaki = $totalKaryawan > 0 ? round($totalLaki / $totalKaryawan * 100) : 0; @endphp
                    <div class="mb-5">
                        <div class="flex justify-between text-xs text-gray-400 mb-1">
                            <span>Proporsi dari total karyawan</span>
                            <span class="font-semibold text-blue-600">{{ $pctLaki }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $pctLaki }}%"></div>
                        </div>
                    </div>

                    {{-- Chart + Legenda --}}
                    <div class="flex items-center gap-5">
                        <div class="relative flex-shrink-0" style="width:120px;height:120px">
                            <canvas id="chartLaki"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <div class="text-center">
                                    <p class="text-lg font-bold text-gray-800">{{ $totalLaki }}</p>
                                    <p class="text-xs text-gray-400">total</p>
                                </div>
                            </div>
                        </div>
                        <ul class="flex-1 space-y-2.5">
                            @php
                                $blueShades = ['#2563eb','#3b82f6','#60a5fa','#93c5fd','#bfdbfe','#dbeafe'];
                            @endphp
                            @foreach ($kontrakLaki as $i => $item)
                            @php
                                $pct = $totalLaki > 0 ? round($item->total / $totalLaki * 100) : 0;
                                $color = $blueShades[$i % count($blueShades)];
                            @endphp
                            <li>
                                <div class="flex items-center justify-between text-xs mb-1">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{ $color }}"></span>
                                        <span class="text-gray-600 truncate max-w-[110px]">{{ $item->nama_kontrak }}</span>
                                    </div>
                                    <span class="font-bold text-gray-800 ml-2">{{ $item->total }} <span class="text-gray-400 font-normal">({{ $pct }}%)</span></span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1">
                                    <div class="h-1 rounded-full" style="width:{{ $pct }}%;background:{{ $color }}"></div>
                                </div>
                            </li>
                            @endforeach
                            @if($kontrakLaki->isEmpty())
                                <li class="text-xs text-gray-400 text-center py-4">Belum ada data</li>
                            @endif
                        </ul>
                    </div>
                </div>

                {{-- PEREMPUAN --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-pink-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="7" r="4"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 21a8.38 8.38 0 0113 0"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-800">Perempuan</h3>
                                <p class="text-xs text-gray-400">Distribusi per jenis kontrak</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-extrabold text-pink-500">{{ number_format($totalPerempuan) }}</p>
                            <p class="text-xs text-gray-400">karyawan</p>
                        </div>
                    </div>

                    @php $pctPerempuan = $totalKaryawan > 0 ? round($totalPerempuan / $totalKaryawan * 100) : 0; @endphp
                    <div class="mb-5">
                        <div class="flex justify-between text-xs text-gray-400 mb-1">
                            <span>Proporsi dari total karyawan</span>
                            <span class="font-semibold text-pink-500">{{ $pctPerempuan }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-pink-400 h-2 rounded-full" style="width: {{ $pctPerempuan }}%"></div>
                        </div>
                    </div>

                    {{-- Chart + Legenda --}}
                    <div class="flex items-center gap-5">
                        <div class="relative flex-shrink-0" style="width:120px;height:120px">
                            <canvas id="chartPerempuan"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <div class="text-center">
                                    <p class="text-lg font-bold text-gray-800">{{ $totalPerempuan }}</p>
                                    <p class="text-xs text-gray-400">total</p>
                                </div>
                            </div>
                        </div>
                        <ul class="flex-1 space-y-2.5">
                            @php
                                $pinkShades = ['#db2777','#ec4899','#f472b6','#f9a8d4','#fce7f3','#fdf2f8'];
                            @endphp
                            @foreach ($kontrakPerempuan as $i => $item)
                            @php
                                $pct = $totalPerempuan > 0 ? round($item->total / $totalPerempuan * 100) : 0;
                                $color = $pinkShades[$i % count($pinkShades)];
                            @endphp
                            <li>
                                <div class="flex items-center justify-between text-xs mb-1">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{ $color }}"></span>
                                        <span class="text-gray-600 truncate max-w-[110px]">{{ $item->nama_kontrak }}</span>
                                    </div>
                                    <span class="font-bold text-gray-800 ml-2">{{ $item->total }} <span class="text-gray-400 font-normal">({{ $pct }}%)</span></span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1">
                                    <div class="h-1 rounded-full" style="width:{{ $pct }}%;background:{{ $color }}"></div>
                                </div>
                            </li>
                            @endforeach
                            @if($kontrakPerempuan->isEmpty())
                                <li class="text-xs text-gray-400 text-center py-4">Belum ada data</li>
                            @endif
                        </ul>
                    </div>
                </div>

            </div>

            {{-- ══════════════════════════════════════════════════════
                 BARIS 4 — Chart Jabatan + Pendidikan & Kontrak
            ══════════════════════════════════════════════════════ --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Chart Jabatan --}}
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-gray-700">Distribusi per Jabatan</h3>
                        <span class="text-xs text-gray-400">Top {{ $karyawanPerJabatan->count() }}</span>
                    </div>
                    <div class="relative h-56">
                        <canvas id="chartJabatan"></canvas>
                    </div>
                </div>

                {{-- Chart Pendidikan + Kontrak --}}
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 space-y-5">

                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Distribusi per Pendidikan</h3>
                        <div class="flex items-center gap-5">
                            <div class="relative w-32 h-32 flex-shrink-0">
                                <canvas id="chartPendidikan"></canvas>
                            </div>
                            <ul class="text-xs text-gray-600 space-y-1.5 flex-1 min-w-0">
                                @php $palette = ['#3b82f6','#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#14b8a6','#f43f5e']; @endphp
                                @foreach ($karyawanPerPendidikan as $idx => $item)
                                <li class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:{{ $palette[$idx % count($palette)] }}"></span>
                                        <span class="truncate">{{ $item->nama_pendidikan }}</span>
                                    </div>
                                    <span class="font-semibold text-gray-800 flex-shrink-0">{{ $item->total }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Jenis Kontrak</h3>
                        <ul class="space-y-2.5">
                            @foreach ($karyawanPerKontrak as $idx => $item)
                            @php
                                $pct = $totalKaryawan > 0 ? round($item->total / $totalKaryawan * 100) : 0;
                                $barColor = $palette[$idx % count($palette)];
                            @endphp
                            <li>
                                <div class="flex justify-between text-xs mb-1">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full" style="background:{{ $barColor }}"></span>
                                        <span class="text-gray-600">{{ $item->nama_kontrak }}</span>
                                    </div>
                                    <span class="font-semibold text-gray-800">{{ $item->total }} <span class="text-gray-400 font-normal">({{ $pct }}%)</span></span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full" style="width: {{ $pct }}%;background:{{ $barColor }}"></div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════
                 BARIS 5 — Tabel Karyawan Terbaru
            ══════════════════════════════════════════════════════ --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-700">Karyawan Terbaru</h3>
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
                                <td class="px-6 py-3 text-gray-500 font-mono text-xs">{{ $k->nip ?? '-' }}</td>
                                <td class="px-6 py-3 text-gray-600">{{ $k->jabatan?->nama_jabatan ?? '-' }}</td>
                                <td class="px-6 py-3 text-gray-600">{{ $k->jenisKontrak?->nama_kontrak ?? '-' }}</td>
                                <td class="px-6 py-3 text-gray-500">
                                    {{ $k->tanggal_masuk ? \Carbon\Carbon::parse($k->tanggal_masuk)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-3">
                                    @php
                                        $badge = match($k->status_aktif) {
                                            'Aktif'   => 'bg-emerald-100 text-emerald-700',
                                            'Cuti'    => 'bg-amber-100 text-amber-700',
                                            'Pensiun' => 'bg-slate-100 text-slate-600',
                                            'Resign'  => 'bg-red-100 text-red-600',
                                            default   => 'bg-gray-100 text-gray-600',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badge }}">{{ $k->status_aktif }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada data karyawan.</td>
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
        const blueShades  = ['#2563eb','#3b82f6','#60a5fa','#93c5fd','#bfdbfe'];
        const pinkShades  = ['#db2777','#ec4899','#f472b6','#f9a8d4','#fce7f3'];

        // ── Chart Jabatan (Horizontal Bar) ──────────────────────────────────
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

        // ── Chart Laki-laki per Jenis Kontrak (Doughnut) ─────────────────────
        new Chart(document.getElementById('chartLaki'), {
            type: 'doughnut',
            data: {
                labels: @json($kontrakLaki->pluck('nama_kontrak')),
                datasets: [{
                    data: @json($kontrakLaki->pluck('total')),
                    backgroundColor: blueShades,
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

        // ── Chart Perempuan per Jenis Kontrak (Doughnut) ─────────────────────
        new Chart(document.getElementById('chartPerempuan'), {
            type: 'doughnut',
            data: {
                labels: @json($kontrakPerempuan->pluck('nama_kontrak')),
                datasets: [{
                    data: @json($kontrakPerempuan->pluck('total')),
                    backgroundColor: pinkShades,
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
