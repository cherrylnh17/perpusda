<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('karyawan.index') }}"
               class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Karyawan</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-5">

            {{-- ── Header Card ─────────────────────────────────────────────── --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-6">
                    @if ($karyawan->foto)
                        <img src="{{ asset('storage/' . $karyawan->foto) }}"
                             alt="{{ $karyawan->nama_lengkap }}"
                             class="w-24 h-24 rounded-2xl object-cover ring-4 ring-gray-100 flex-shrink-0">
                    @else
                        <div class="w-24 h-24 rounded-2xl bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-3xl flex-shrink-0">
                            {{ strtoupper(substr($karyawan->nama_lengkap, 0, 1)) }}
                        </div>
                    @endif

                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-800">{{ $karyawan->nama_lengkap }}</h2>
                        <p class="text-gray-500 text-sm mt-0.5">{{ $karyawan->jabatan?->nama_jabatan ?? 'Jabatan belum diisi' }}</p>
                        <div class="flex items-center gap-3 mt-3">
                            @php
                                $badge = match($karyawan->status_aktif) {
                                    'Aktif'   => 'bg-green-100 text-green-700',
                                    'Pensiun' => 'bg-gray-100 text-gray-500',
                                    default   => 'bg-gray-100 text-gray-500',
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                {{ $karyawan->status_aktif }}
                            </span>
                            <span class="text-xs text-gray-400 font-mono">NIP: {{ $karyawan->nip }}</span>
                        </div>
                    </div>

                    <a href="{{ route('karyawan.edit', $karyawan) }}"
                       class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 text-sm font-medium rounded-xl transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                </div>
            </div>

            {{-- ── Jadwal & Pengajuan Kenaikan ──────────────────────────────── --}}
            @php
                $today       = now()->startOfDay();
                $berkalaDue  = $karyawan->tanggal_berkala_berikutnya;
                $berkalaSisa = $berkalaDue ? $today->diffInDays($berkalaDue, false) : null;
            @endphp

            @if ($berkalaDue || $karyawan->pengajuanBerkalaPending || $karyawan->pengajuanGolonganPending)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Countdown Kenaikan Berkala --}}
                @if ($berkalaDue)
                @php
                    $bWarna = $berkalaSisa !== null && $berkalaSisa <= 30 && $berkalaSisa >= 0
                        ? 'bg-amber-50 border-amber-200'
                        : ($berkalaSisa < 0 ? 'bg-gray-50 border-gray-200' : 'bg-green-50 border-green-200');
                    $bTextWarna = $berkalaSisa !== null && $berkalaSisa <= 30 && $berkalaSisa >= 0
                        ? 'text-amber-700'
                        : ($berkalaSisa < 0 ? 'text-gray-500' : 'text-green-700');
                @endphp
                <div class="rounded-2xl border {{ $bWarna }} p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-xl bg-white/80 flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 {{ $bTextWarna }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Kenaikan Berkala Berikutnya</p>
                        @if ($berkalaSisa !== null && $berkalaSisa <= 30 && $berkalaSisa >= 0)
                            <span class="ml-auto px-2 py-0.5 rounded-full bg-amber-200 text-amber-800 text-xs font-bold">H-{{ $berkalaSisa }}</span>
                        @endif
                    </div>
                    <p class="text-sm font-bold {{ $bTextWarna }}">{{ $berkalaDue->format('d F Y') }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        @if ($berkalaSisa === null)
                            —
                        @elseif ($berkalaSisa < 0)
                            Sudah lewat {{ abs($berkalaSisa) }} hari lalu
                        @elseif ($berkalaSisa === 0)
                            Hari ini
                        @else
                            {{ $berkalaSisa }} hari lagi
                        @endif
                    </p>
                    @if ($karyawan->tanggal_berkala_terakhir)
                        <p class="text-xs text-gray-400 mt-2 pt-2 border-t border-white/60">
                            Terakhir disetujui: {{ $karyawan->tanggal_berkala_terakhir->format('d F Y') }}
                        </p>
                    @endif
                </div>
                @endif

                {{-- Pengajuan Kenaikan Golongan (Pending) --}}
                @if ($karyawan->pengajuanGolonganPending)
                <div class="rounded-2xl border bg-blue-50 border-blue-200 p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-xl bg-white/80 flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Pengajuan Kenaikan Golongan</p>
                        <span class="ml-auto px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold">Pending</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm font-bold text-blue-700">
                        <span>{{ $karyawan->pengajuanGolonganPending->golonganLama?->nama_golongan ?? '-' }}</span>
                        <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                        <span>{{ $karyawan->pengajuanGolonganPending->golonganBaru?->nama_golongan ?? '-' }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        Efektif: {{ $karyawan->pengajuanGolonganPending->tanggal_efektif->format('d F Y') }}
                    </p>
                    @if ($karyawan->pengajuanGolonganPending->catatan)
                        <p class="text-xs text-gray-400 mt-1 italic">{{ $karyawan->pengajuanGolonganPending->catatan }}</p>
                    @endif
                </div>
                @endif

                {{-- Pengajuan Kenaikan Berkala (Pending) --}}
                @if ($karyawan->pengajuanBerkalaPending)
                <div class="rounded-2xl border bg-amber-50 border-amber-200 p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-xl bg-white/80 flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Pengajuan Kenaikan Berkala</p>
                        <span class="ml-auto px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold">Pending</span>
                    </div>
                    <p class="text-sm font-bold text-amber-700">
                        Efektif: {{ $karyawan->pengajuanBerkalaPending->tanggal_efektif->format('d F Y') }}
                    </p>
                    @if ($karyawan->pengajuanBerkalaPending->catatan)
                        <p class="text-xs text-gray-400 mt-1 italic">{{ $karyawan->pengajuanBerkalaPending->catatan }}</p>
                    @endif
                </div>
                @endif

            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- ── Data Pribadi ─────────────────────────────────────────── --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Data Pribadi</h3>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500">NIK</dt>
                            <dd class="font-mono text-gray-800 font-medium">{{ $karyawan->nik }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500">Tanggal Lahir</dt>
                            <dd class="text-gray-800">{{ $karyawan->tanggal_lahir?->format('d F Y') ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500">Jenis Kelamin</dt>
                            <dd class="text-gray-800">{{ $karyawan->jenis_kelamin ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500">Agama</dt>
                            <dd class="text-gray-800">{{ $karyawan->agama ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500">Golongan Darah</dt>
                            <dd class="text-gray-800">{{ $karyawan->golongan_darah ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500">Pendidikan</dt>
                            <dd class="text-gray-800">{{ $karyawan->pendidikan?->nama_pendidikan ?? '-' }}</dd>
                        </div>
                        <div class="pt-2 border-t border-gray-50">
                            <dt class="text-gray-500 mb-1">Alamat</dt>
                            <dd class="text-gray-800 leading-relaxed">{{ $karyawan->alamat ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- ── Data Kepegawaian ─────────────────────────────────────── --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Data Kepegawaian</h3>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500">Jenis Kontrak</dt>
                            <dd class="text-gray-800">{{ $karyawan->jenisKontrak?->nama_kontrak ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500">Golongan</dt>
                            <dd class="text-gray-800">
                                @if ($karyawan->golongan)
                                    <span class="inline-flex items-center gap-1">
                                        {{ $karyawan->golongan->nama_golongan }}
                                        <span class="text-xs text-gray-400">({{ $karyawan->golongan->tipe }})</span>
                                    </span>
                                @else
                                    -
                                @endif
                            </dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500">Tanggal Masuk</dt>
                            <dd class="text-gray-800">{{ $karyawan->tanggal_masuk?->format('d F Y') ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500">Mulai Golongan</dt>
                            <dd class="text-gray-800">{{ $karyawan->tanggal_mulai_golongan?->format('d F Y') ?? '-' }}</dd>
                        </div>
                        @if ($karyawan->jenisKontrak?->jam_kerja_sehari)
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500">Jam Kerja / Hari</dt>
                            <dd class="text-gray-800">{{ $karyawan->jenisKontrak->jam_kerja_sehari }} jam</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            {{-- ── Riwayat Kenaikan Berkala ─────────────────────────────────── --}}
            @if ($karyawan->pengajuanBerkalas->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
                    <div class="w-7 h-7 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wide">Riwayat Kenaikan Berkala</h3>
                    <span class="ml-auto text-xs text-gray-400">5 terbaru</span>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach ($karyawan->pengajuanBerkalas as $berkala)
                    <div class="px-6 py-3 flex items-center gap-4 text-sm">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-gray-400 text-xs">{{ $berkala->tanggal_efektif?->format('d M Y') }}</span>
                                @php
                                    $bBadge = match($berkala->status) {
                                        'approved' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-600',
                                        default    => 'bg-yellow-100 text-yellow-700',
                                    };
                                @endphp
                                <span class="px-1.5 py-0.5 rounded text-xs font-semibold {{ $bBadge }}">
                                    {{ ucfirst($berkala->status) }}
                                </span>
                            </div>
                            @if ($berkala->catatan)
                                <p class="text-xs text-gray-400 mt-0.5 italic">{{ $berkala->catatan }}</p>
                            @endif
                            @if ($berkala->diprosesByUser)
                                <p class="text-xs text-gray-400 mt-0.5">Diproses oleh: {{ $berkala->diprosesByUser->name }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── Riwayat Kenaikan Golongan ────────────────────────────────── --}}
            @if ($karyawan->historiGolongans->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wide">Riwayat Kenaikan Golongan</h3>
                    <span class="ml-auto text-xs text-gray-400">5 terbaru</span>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach ($karyawan->historiGolongans as $histori)
                    <div class="px-6 py-3 flex items-center gap-4 text-sm">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-gray-400 text-xs">{{ $histori->tanggal_efektif?->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2 mt-1 text-xs">
                                <span class="text-gray-500">{{ $histori->golonganLama?->nama_golongan ?? '-' }}</span>
                                <svg class="w-3 h-3 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                                <span class="font-semibold text-blue-700">{{ $histori->golonganBaru?->nama_golongan ?? '-' }}</span>
                            </div>
                            @if ($histori->dicatatByUser)
                                <p class="text-xs text-gray-400 mt-0.5">Dicatat oleh: {{ $histori->dicatatByUser->name }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── Info Sistem ──────────────────────────────────────────────── --}}
            <div class="flex items-center justify-between text-xs text-gray-400 pb-4">
                <span>Dibuat: {{ $karyawan->created_at->format('d M Y, H:i') }}</span>
                <span>Diperbarui: {{ $karyawan->updated_at->format('d M Y, H:i') }}</span>
            </div>

        </div>
    </div>
</x-app-layout>
