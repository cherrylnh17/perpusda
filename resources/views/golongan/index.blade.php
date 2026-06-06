<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Golongan</h2>
            <a href="{{ route('golongan.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Golongan
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Search & Filter --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <form method="GET" action="{{ route('golongan.index') }}" class="flex flex-wrap gap-3">

                    {{-- Filter Tipe --}}
                    <div class="flex gap-1 p-1 bg-gray-100 rounded-xl">
                        <a href="{{ route('golongan.index', array_merge(request()->except('tipe', 'page'), [])) }}"
                           class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors
                                  {{ !request('tipe') ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                            Semua
                        </a>
                        <a href="{{ route('golongan.index', array_merge(request()->except('tipe', 'page'), ['tipe' => 'PNS'])) }}"
                           class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors
                                  {{ request('tipe') === 'PNS' ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                            PNS
                        </a>
                        <a href="{{ route('golongan.index', array_merge(request()->except('tipe', 'page'), ['tipe' => 'PPPK'])) }}"
                           class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors
                                  {{ request('tipe') === 'PPPK' ? 'bg-white text-purple-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                            PPPK
                        </a>
                    </div>

                    {{-- Search --}}
                    <div class="flex-1 min-w-48 relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama golongan..."
                               class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                        {{-- Preserve tipe filter when searching --}}
                        @if(request('tipe'))
                            <input type="hidden" name="tipe" value="{{ request('tipe') }}">
                        @endif
                    </div>

                    <button type="submit"
                            class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('golongan.index', request('tipe') ? ['tipe' => request('tipe')] : []) }}"
                           class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-xl transition-colors">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide w-12">#</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide w-28">Tipe</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Golongan</th>
                            <th class="text-center px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Jumlah Karyawan</th>
                            <th class="text-right px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($golongans as $g)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-gray-400">
                                    {{ ($golongans->currentPage() - 1) * $golongans->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full
                                        {{ $g->tipe === 'PNS' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                        {{ $g->tipe }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $g->nama_golongan }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-semibold rounded-full
                                        {{ $g->karyawans_count > 0 ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-400' }}">
                                        {{ $g->karyawans_count }} karyawan
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('golongan.edit', $g) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-medium rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>

                                        <form action="{{ route('golongan.destroy', $g) }}" method="POST"
                                              onsubmit="return confirm('Hapus golongan \'{{ $g->nama_golongan }}\'?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium rounded-lg transition-colors
                                                    {{ $g->karyawans_count > 0 ? 'opacity-40 cursor-not-allowed' : '' }}"
                                                    {{ $g->karyawans_count > 0 ? 'disabled' : '' }}>
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M8 7V5a1 1 0 011-1h6a1 1 0 011 1v2"/>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                    <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-3-3v6M4.5 19.5l15-15M3 10.5a7.5 7.5 0 1115 0 7.5 7.5 0 01-15 0z"/>
                                    </svg>
                                    <p class="text-sm">Belum ada data golongan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($golongans->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $golongans->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
