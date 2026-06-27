<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            💰 Kenaikan Berkala
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
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('kenaikan-berkala.index', ['rentang' => '30']) }}"
                   class="flex items-center gap-4 bg-white rounded-2xl border {{ $rentang === '30' ? 'border-green-400 ring-2 ring-green-200' : 'border-gray-100' }} shadow-sm p-5 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalBerkalaH30 }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Kenaikan Berkala H-30</p>
                    </div>
                </a>

                <a href="{{ route('kenaikan-berkala.index', ['rentang' => 'semua']) }}"
                   class="flex items-center gap-4 bg-white rounded-2xl border {{ $rentang === 'semua' ? 'border-amber-400 ring-2 ring-amber-200' : 'border-gray-100' }} shadow-sm p-5 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ $karyawans->total() }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Semua Jadwal Berkala</p>
                    </div>
                </a>
            </div>

            {{-- ── Toolbar: Filter ─────────────────────────────────────────── --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <form method="GET" action="{{ route('kenaikan-berkala.index') }}">
                    <div class="flex flex-col lg:flex-row gap-3">

                        {{-- Search --}}
                        <div class="relative flex-1">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Cari nama atau NIP..."
                                   class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
                        </div>

                        {{-- Filter Rentang --}}
                        <select name="rentang" onchange="this.form.submit()"
                                class="text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-green-500 outline-none text-gray-600 bg-white">
                            <option value="7"     {{ $rentang === '7'     ? 'selected' : '' }}>H-7 (minggu ini)</option>
                            <option value="14"    {{ $rentang === '14'    ? 'selected' : '' }}>H-14 (2 minggu)</option>
                            <option value="30"    {{ $rentang === '30'    ? 'selected' : '' }}>H-30 (bulan ini)</option>
                            <option value="semua" {{ $rentang === 'semua' ? 'selected' : '' }}>Semua jadwal</option>
                        </select>

                        {{-- Tombol Cari --}}
                        <button type="submit"
                                class="px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-xl transition-colors">
                            Cari
                        </button>

                        @if (request()->hasAny(['search', 'rentang']))
                        <a href="{{ route('kenaikan-berkala.index') }}"
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
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
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
                                <th class="px-5 py-3 text-left">Golongan</th>
                                <th class="px-5 py-3 text-center">Jadwal Berkala</th>
                                <th class="px-5 py-3 text-center">Status</th>
                                <th class="px-5 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($karyawans as $k)
                            @php
                                $today   = now()->startOfDay();

                                $berkalaAktif = $k->kenaikanBerkalaAktif;
                                $berkalaDue   = $berkalaAktif?->tanggal_berikutnya;
                                $berkalaSisa  = $berkalaDue ? (int) $today->diffInDays($berkalaDue, false) : null;
                                $berkalaH7    = $berkalaSisa !== null && $berkalaSisa >= 0 && $berkalaSisa <= 7;
                                $berkalaH30   = $berkalaSisa !== null && $berkalaSisa >= 0 && $berkalaSisa <= 30;
                                $hasPendingBerkala = $berkalaAktif?->status === 'pending';

                                $rowClass = '';
                                if ($berkalaH7) {
                                    $rowClass = 'bg-red-50/40';
                                } elseif ($berkalaH30) {
                                    $rowClass = 'bg-amber-50/30';
                                }
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
                                            <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 text-green-600 font-bold text-sm">
                                                {{ strtoupper(substr($k->nama_lengkap, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('karyawan.show', $k) }}"
                                               class="font-medium text-gray-800 hover:text-green-600 leading-tight">
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

                                {{-- Golongan --}}
                                <td class="px-5 py-3 text-gray-600 text-sm">
                                    @if ($k->golongan)
                                        <span class="inline-flex items-center gap-1">
                                            <span class="font-semibold">{{ $k->golongan->nama_golongan }}</span>
                                            <span class="text-xs text-gray-400">({{ $k->golongan->tipe }})</span>
                                        </span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>

                                {{-- Jadwal Berkala --}}
                                <td class="px-5 py-3 text-center">
                                    @if ($berkalaDue && $berkalaSisa >= 0)
                                        @php
                                            if ($berkalaH7) {
                                                $badgeClass = 'bg-red-100 text-red-700 border border-red-200';
                                                $ring = 'animate-pulse';
                                            } elseif ($berkalaH30) {
                                                $badgeClass = 'bg-green-100 text-green-700 border border-green-200';
                                                $ring = '';
                                            } else {
                                                $badgeClass = 'bg-gray-100 text-gray-500';
                                                $ring = '';
                                            }
                                        @endphp
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold {{ $badgeClass }} {{ $ring }}">
                                                H-{{ $berkalaSisa }}
                                            </span>
                                            <span class="text-xs text-gray-400">{{ $berkalaDue->format('d M Y') }}</span>
                                        </div>
                                    @elseif ($berkalaDue && $berkalaSisa < 0)
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="text-xs text-red-400 font-medium">Terlambat</span>
                                            <span class="text-xs text-gray-400">{{ $berkalaDue->format('d M Y') }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-3 text-center">
                                    @if ($hasPendingBerkala)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                            ⏳ Pending
                                        </span>
                                    @elseif ($berkalaAktif?->status === 'scheduled')
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
                                        @if ($berkalaDue)
                                        <button
                                            type="button"
                                            title="Proses Kenaikan Berkala"
                                            onclick="openModalBerkala(
                                                {{ $k->id_karyawan }},
                                                '{{ addslashes($k->nama_lengkap) }}',
                                                '{{ $berkalaDue->format('Y-m-d') }}',
                                                {{ $hasPendingBerkala ? 'true' : 'false' }}
                                            )"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-green-600 hover:bg-green-700 text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Proses
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-sm">Tidak ada karyawan dengan jadwal kenaikan berkala.</p>
                                        <p class="text-xs text-gray-300">Coba ubah filter rentang hari atau reset pencarian.</p>
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
         MODAL: Proses Kenaikan BERKALA
    ══════════════════════════════════════════════════════════════════════ --}}
    <div id="modalBerkala" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="font-semibold text-gray-800">Proses Kenaikan Berkala</h3>
                    <p id="modalBerkalaNama" class="text-xs text-gray-500 mt-0.5"></p>
                </div>
                <button onclick="closeModalBerkala()"
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Tab Approve / Reject --}}
            <div class="flex border-b border-gray-100 mx-6 mt-4 gap-0">
                <button id="tabApproveBerkala" onclick="switchBerkalaTab('approve')"
                        class="flex-1 py-2.5 text-sm font-semibold text-center rounded-t-lg border-b-2 border-green-500 text-green-600 transition-colors">
                    ✅ Setujui
                </button>
                <button id="tabRejectBerkala" onclick="switchBerkalaTab('reject')"
                        class="flex-1 py-2.5 text-sm font-semibold text-center rounded-t-lg border-b-2 border-transparent text-gray-400 hover:text-gray-600 transition-colors">
                    ❌ Tolak & Jadwal Ulang
                </button>
            </div>

            {{-- Form Approve Berkala --}}
            <form id="formApproveBerkala" method="POST" action="" class="px-6 py-4 space-y-4">
                @csrf

                <div class="p-3 bg-green-50 border border-green-200 rounded-xl text-xs text-green-700">
                    Kenaikan berkala akan disetujui. Sistem akan membuat jadwal berkala berikutnya secara otomatis.
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Berkala Berikutnya <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_berikutnya" id="inputBerkalaTglBerikutnya" required
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
                    <p class="text-xs text-gray-400 mt-1">Default +2 tahun dari jadwal saat ini — ubah jika perlu</p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Catatan</label>
                    <textarea name="catatan" rows="2" maxlength="500"
                              placeholder="Opsional..."
                              class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none resize-none"></textarea>
                </div>

                <div class="flex gap-3 pt-1">
                    <button type="button" onclick="closeModalBerkala()"
                            class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl transition-colors">
                        Setujui Kenaikan
                    </button>
                </div>
            </form>

            {{-- Form Reject Berkala --}}
            <form id="formRejectBerkala" method="POST" action="" class="px-6 py-4 space-y-4 hidden">
                @csrf

                <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-700">
                    Kenaikan ditolak — jadwal berkala akan diperbarui ke tanggal baru.
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Jadwal Berkala Berikutnya <span class="text-red-500">*</span></label>
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
                    <button type="button" onclick="closeModalBerkala()"
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
    const routeApproveBerkala = '{{ route("kenaikan-berkala.approve", ["karyawan" => "__ID__"]) }}';
    const routeRejectBerkala  = '{{ route("kenaikan-berkala.reject",  ["karyawan" => "__ID__"]) }}';

    function makeUrl(template, id) {
        return template.replace('__ID__', id);
    }

    function openModalBerkala(id, nama, tglBerkala, hasPending) {
        document.getElementById('modalBerkalaNama').textContent = nama;

        if (tglBerkala) {
            const d = new Date(tglBerkala);
            d.setFullYear(d.getFullYear() + 2);
            document.getElementById('inputBerkalaTglBerikutnya').value = d.toISOString().split('T')[0];
        }

        document.getElementById('formApproveBerkala').action = makeUrl(routeApproveBerkala, id);
        document.getElementById('formRejectBerkala').action  = makeUrl(routeRejectBerkala, id);

        switchBerkalaTab('approve');
        document.getElementById('modalBerkala').classList.remove('hidden');
    }

    function closeModalBerkala() {
        document.getElementById('modalBerkala').classList.add('hidden');
    }

    function switchBerkalaTab(tab) {
        const approve = document.getElementById('formApproveBerkala');
        const reject  = document.getElementById('formRejectBerkala');
        const tabA    = document.getElementById('tabApproveBerkala');
        const tabR    = document.getElementById('tabRejectBerkala');

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

    document.getElementById('modalBerkala').addEventListener('click', function (e) {
        if (e.target === this) closeModalBerkala();
    });
    </script>

</x-app-layout>
