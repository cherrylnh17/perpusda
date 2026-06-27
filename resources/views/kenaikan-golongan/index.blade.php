<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🏅 Kenaikan Golongan
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
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-3 text-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
            @endif

            {{-- ── Summary Card ───────────────────────────────────────────── --}}
            <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
                <a href="{{ route('kenaikan-golongan.index') }}"
                   class="flex items-center gap-4 bg-white rounded-2xl border border-blue-400 ring-2 ring-blue-200 shadow-sm p-5 hover:shadow-md transition-shadow max-w-sm">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalGolonganPending }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Kenaikan Golongan Pending</p>
                    </div>
                </a>
            </div>

            {{-- ── Toolbar: Filter ─────────────────────────────────────────── --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <form method="GET" action="{{ route('kenaikan-golongan.index') }}">
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

                        {{-- Tombol Cari --}}
                        <button type="submit"
                                class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors">
                            Cari
                        </button>

                        @if (request()->hasAny(['search']))
                        <a href="{{ route('kenaikan-golongan.index') }}"
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
                    <span class="text-gray-400">Diurutkan: nama A–Z</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide border-b border-gray-100">
                            <tr>
                                <th class="px-5 py-3 text-left w-10">#</th>
                                <th class="px-5 py-3 text-left">Karyawan</th>
                                <th class="px-5 py-3 text-left">NIP</th>
                                <th class="px-5 py-3 text-center">Golongan Saat Ini</th>
                                <th class="px-5 py-3 text-center">Golongan Baru</th>
                                <th class="px-5 py-3 text-center">Tanggal Efektif</th>
                                <th class="px-5 py-3 text-center">Status</th>
                                <th class="px-5 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($karyawans as $k)
                            @php
                                $golonganAktif = $k->kenaikanGolonganAktif;
                            @endphp
                            <tr class="hover:bg-gray-50/60 transition-colors group">

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
                                            <p class="text-xs text-gray-400">{{ $k->jabatan?->nama_jabatan ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- NIP --}}
                                <td class="px-5 py-3 font-mono text-xs text-gray-600">
                                    {{ $k->nip }}
                                </td>

                                {{-- Golongan Saat Ini --}}
                                <td class="px-5 py-3 text-center">
                                    @if ($k->golongan)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                            {{ $k->golongan->nama_golongan }}
                                            <span class="text-gray-400">({{ $k->golongan->tipe }})</span>
                                        </span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>

                                {{-- Golongan Baru --}}
                                <td class="px-5 py-3 text-center">
                                    @if ($golonganAktif?->golonganBaru)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700 border border-purple-200">
                                            {{ $golonganAktif->golonganBaru->nama_golongan }}
                                            <span class="text-purple-400">({{ $golonganAktif->golonganBaru->tipe }})</span>
                                        </span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>

                                {{-- Tanggal Efektif --}}
                                <td class="px-5 py-3 text-center">
                                    @if ($golonganAktif?->tanggal_berikutnya)
                                        <span class="text-xs text-gray-600 font-medium">
                                            {{ $golonganAktif->tanggal_berikutnya->format('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-3 text-center">
                                    @if ($golonganAktif?->status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                            ⏳ Menunggu
                                        </span>
                                    @elseif ($golonganAktif?->status === 'scheduled')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                            📅 Terjadwal
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        @if ($golonganAktif && $golonganAktif->status === 'pending')
                                        <button
                                            type="button"
                                            title="Proses Kenaikan Golongan"
                                            onclick="openModalGolongan(
                                                {{ $k->id_karyawan }},
                                                '{{ addslashes($k->nama_lengkap) }}',
                                                '{{ addslashes($k->golongan?->nama_golongan ?? '') }}',
                                                '{{ $golonganAktif->golongan_baru_id }}',
                                                '{{ $golonganAktif->tanggal_berikutnya?->format('Y-m-d') }}'
                                            )"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                            </svg>
                                            Proses
                                        </button>
                                        @else
                                            <span class="text-xs text-gray-300">—</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                        </svg>
                                        <p class="text-sm">Tidak ada pengajuan kenaikan golongan yang aktif.</p>
                                        <p class="text-xs text-gray-300">Semua pengajuan sudah diproses atau belum ada pengajuan baru.</p>
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
         MODAL: Proses Kenaikan GOLONGAN
    ══════════════════════════════════════════════════════════════════════ --}}
    <div id="modalGolongan" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="font-semibold text-gray-800">Proses Kenaikan Golongan</h3>
                    <p id="modalGolonganNama" class="text-xs text-gray-500 mt-0.5"></p>
                </div>
                <button onclick="closeModalGolongan()"
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Info golongan sekarang --}}
            <div class="mx-6 mt-4 p-3 bg-gray-50 rounded-xl flex items-center justify-between text-sm">
                <span class="text-gray-500">Golongan Sekarang</span>
                <span id="modalGolonganSekarang" class="font-semibold text-gray-800"></span>
            </div>

            {{-- Tab Approve / Reject --}}
            <div class="flex border-b border-gray-100 mx-6 mt-4 gap-0">
                <button id="tabApproveGolongan" onclick="switchGolonganTab('approve')"
                        class="flex-1 py-2.5 text-sm font-semibold text-center rounded-t-lg border-b-2 border-blue-500 text-blue-600 transition-colors">
                    ✅ Setujui
                </button>
                <button id="tabRejectGolongan" onclick="switchGolonganTab('reject')"
                        class="flex-1 py-2.5 text-sm font-semibold text-center rounded-t-lg border-b-2 border-transparent text-gray-400 hover:text-gray-600 transition-colors">
                    ❌ Tolak
                </button>
            </div>

            {{-- Form Approve Golongan --}}
            <form id="formApproveGolongan" method="POST" action="" class="px-6 py-4 space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Golongan Baru <span class="text-red-500">*</span></label>
                    <select name="golongan_baru_id" id="selectGolonganBaru" required
                            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none bg-white">
                        <option value="">-- Pilih golongan baru --</option>
                        @foreach ($golongans as $gol)
                        <option value="{{ $gol->id_golongan }}">{{ $gol->nama_golongan }} ({{ $gol->tipe }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Tanggal Efektif <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_efektif" id="inputGolonganTglEfektif" required
                               class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Berkala Berikutnya</label>
                        <input type="date" name="tanggal_berikutnya"
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
                    <button type="button" onclick="closeModalGolongan()"
                            class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors">
                        Setujui Kenaikan
                    </button>
                </div>
            </form>

            {{-- Form Reject Golongan --}}
            <form id="formRejectGolongan" method="POST" action="" class="px-6 py-4 space-y-4 hidden">
                @csrf

                <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-700">
                    Pengajuan kenaikan golongan akan ditolak. Golongan karyawan tidak berubah.
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Alasan Penolakan</label>
                    <textarea name="catatan" rows="3" maxlength="500"
                              placeholder="Jelaskan alasan penolakan..."
                              class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-400 focus:border-transparent outline-none resize-none"></textarea>
                </div>

                <div class="flex gap-3 pt-1">
                    <button type="button" onclick="closeModalGolongan()"
                            class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-colors">
                        Tolak Pengajuan
                    </button>
                </div>
            </form>

        </div>
    </div>


    {{-- ══ JavaScript ══════════════════════════════════════════════════════ --}}
    <script>
    const routeApproveGolongan = '{{ route("kenaikan-golongan.approve", ["karyawan" => "__ID__"]) }}';
    const routeRejectGolongan  = '{{ route("kenaikan-golongan.reject",  ["karyawan" => "__ID__"]) }}';

    function makeUrl(template, id) {
        return template.replace('__ID__', id);
    }

    function openModalGolongan(id, nama, golonganSekarang, golonganBaruId, tglEfektif) {
        document.getElementById('modalGolonganNama').textContent      = nama;
        document.getElementById('modalGolonganSekarang').textContent  = golonganSekarang || '-';
        document.getElementById('inputGolonganTglEfektif').value      = tglEfektif || '';

        const sel = document.getElementById('selectGolonganBaru');
        if (golonganBaruId) sel.value = golonganBaruId;

        document.getElementById('formApproveGolongan').action = makeUrl(routeApproveGolongan, id);
        document.getElementById('formRejectGolongan').action  = makeUrl(routeRejectGolongan, id);

        switchGolonganTab('approve');
        document.getElementById('modalGolongan').classList.remove('hidden');
    }

    function closeModalGolongan() {
        document.getElementById('modalGolongan').classList.add('hidden');
    }

    function switchGolonganTab(tab) {
        const approve = document.getElementById('formApproveGolongan');
        const reject  = document.getElementById('formRejectGolongan');
        const tabA    = document.getElementById('tabApproveGolongan');
        const tabR    = document.getElementById('tabRejectGolongan');

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

    document.getElementById('modalGolongan').addEventListener('click', function (e) {
        if (e.target === this) closeModalGolongan();
    });
    </script>

</x-app-layout>
