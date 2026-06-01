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
                                    'Cuti'    => 'bg-yellow-100 text-yellow-700',
                                    'Pensiun' => 'bg-gray-100 text-gray-500',
                                    'Resign'  => 'bg-red-100 text-red-600',
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
                            <dt class="text-gray-500">Jam Kerja / Hari</dt>
                            <dd class="text-gray-800">{{ $karyawan->jenisKontrak?->jam_kerja_sehari ?? '-' }} jam</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500">Tanggal Masuk</dt>
                            <dd class="text-gray-800">{{ $karyawan->tanggal_masuk?->format('d F Y') }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500">Mulai Jabatan</dt>
                            <dd class="text-gray-800">{{ $karyawan->tanggal_mulai_jabatan?->format('d F Y') }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 pt-2 border-t border-gray-50">
                            <dt class="text-gray-500 font-medium">Gaji</dt>
                            <dd class="text-blue-700 font-bold text-base">
                                Rp {{ number_format($karyawan->gaji, 0, ',', '.') }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- ── Info Sistem ──────────────────────────────────────────────── --}}
            <div class="flex items-center justify-between text-xs text-gray-400 pb-4">
                <span>Dibuat: {{ $karyawan->created_at->format('d M Y, H:i') }}</span>
                <span>Diperbarui: {{ $karyawan->updated_at->format('d M Y, H:i') }}</span>
            </div>

        </div>
    </div>
</x-app-layout>
