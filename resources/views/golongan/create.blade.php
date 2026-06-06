<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('golongan.index') }}"
               class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Golongan</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('golongan.store') }}" method="POST">
                @csrf

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">

                    {{-- Tipe --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tipe <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-3">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="tipe" value="PNS"
                                       class="peer sr-only"
                                       {{ old('tipe') === 'PNS' ? 'checked' : '' }}>
                                <div class="flex items-center justify-center gap-2 px-4 py-2.5 border-2 rounded-xl text-sm font-medium transition-all
                                            border-gray-200 text-gray-500
                                            peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    PNS
                                </div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="tipe" value="PPPK"
                                       class="peer sr-only"
                                       {{ old('tipe') === 'PPPK' ? 'checked' : '' }}>
                                <div class="flex items-center justify-center gap-2 px-4 py-2.5 border-2 rounded-xl text-sm font-medium transition-all
                                            border-gray-200 text-gray-500
                                            peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-checked:text-purple-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    PPPK
                                </div>
                            </label>
                        </div>
                        @error('tipe')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Nama Golongan --}}
                    <div>
                        <label for="nama_golongan" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nama Golongan <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nama_golongan"
                               id="nama_golongan"
                               value="{{ old('nama_golongan') }}"
                               autofocus
                               placeholder="Contoh: I/a, II/b, III/c ..."
                               class="w-full px-3 py-2.5 text-sm border rounded-xl outline-none transition-shadow
                                      focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                      @error('nama_golongan') border-red-400 bg-red-50 @else border-gray-200 @enderror">
                        @error('nama_golongan')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('golongan.index') }}"
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
