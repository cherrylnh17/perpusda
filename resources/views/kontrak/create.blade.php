<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kontrak.index') }}"
               class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Jenis Kontrak</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('kontrak.store') }}" method="POST">
                @csrf

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">

                    {{-- Nama Kontrak --}}
                    <div>
                        <label for="nama_kontrak" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nama Kontrak <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nama_kontrak"
                               id="nama_kontrak"
                               value="{{ old('nama_kontrak') }}"
                               autofocus
                               placeholder="Contoh: Full Time, Part Time, Magang ..."
                               class="w-full px-3 py-2.5 text-sm border rounded-xl outline-none transition-shadow
                                      focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                      @error('nama_kontrak') border-red-400 bg-red-50 @else border-gray-200 @enderror">
                        @error('nama_kontrak')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Jam Kerja --}}
                    <div>
                        <label for="jam_kerja_sehari" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Jam Kerja per Hari <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number"
                                   name="jam_kerja_sehari"
                                   id="jam_kerja_sehari"
                                   value="{{ old('jam_kerja_sehari') }}"
                                   min="1" max="24"
                                   placeholder="8"
                                   class="w-full px-3 py-2.5 pr-14 text-sm border rounded-xl outline-none transition-shadow
                                          focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                          @error('jam_kerja_sehari') border-red-400 bg-red-50 @else border-gray-200 @enderror">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium">jam</span>
                        </div>
                        @error('jam_kerja_sehari')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1.5 text-xs text-gray-400">Masukkan angka antara 1 – 24</p>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('kontrak.index') }}"
                           class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
