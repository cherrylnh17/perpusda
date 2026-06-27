<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-100 rounded-xl">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Kenaikan</h2>
                <p class="text-sm text-gray-500">Kelola kenaikan berkala dan golongan karyawan</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Card Kenaikan Berkala --}}
                <a href="{{ route('kenaikan-berkala.index') }}"
                   class="group block bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:border-green-300 transition-all duration-200 overflow-hidden">
                    <div class="p-8">
                        <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center mb-5 group-hover:bg-green-200 transition-colors">
                            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2 group-hover:text-green-700 transition-colors">Kenaikan Berkala</h3>
                        <p class="text-sm text-gray-500 leading-relaxed mb-4">
                            Kelola kenaikan gaji berkala karyawan. Pantau jadwal kenaikan yang akan datang, setujui atau jadwal ulang proses kenaikan.
                        </p>
                        <div class="flex items-center gap-2 text-sm font-medium text-green-600 group-hover:gap-3 transition-all">
                            <span>Lihat Kenaikan Berkala</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                    <div class="h-1.5 bg-gradient-to-r from-green-400 to-emerald-500"></div>
                </a>

                {{-- Card Kenaikan Golongan --}}
                <a href="{{ route('kenaikan-golongan.index') }}"
                   class="group block bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:border-violet-300 transition-all duration-200 overflow-hidden">
                    <div class="p-8">
                        <div class="w-14 h-14 rounded-2xl bg-violet-100 flex items-center justify-center mb-5 group-hover:bg-violet-200 transition-colors">
                            <svg class="w-7 h-7 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2 group-hover:text-violet-700 transition-colors">Kenaikan Golongan</h3>
                        <p class="text-sm text-gray-500 leading-relaxed mb-4">
                            Kelola kenaikan pangkat/golongan karyawan. Proses pengajuan kenaikan golongan dan pilih golongan baru yang sesuai.
                        </p>
                        <div class="flex items-center gap-2 text-sm font-medium text-violet-600 group-hover:gap-3 transition-all">
                            <span>Lihat Kenaikan Golongan</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                    <div class="h-1.5 bg-gradient-to-r from-violet-400 to-purple-500"></div>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>
