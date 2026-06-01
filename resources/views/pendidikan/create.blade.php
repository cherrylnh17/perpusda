<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('pendidikan.index') }}"
               class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Pendidikan</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('pendidikan.store') }}" method="POST">
                @csrf

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">

                    <div>
                        <label for="nama_pendidikan" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nama Pendidikan <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nama_pendidikan"
                               id="nama_pendidikan"
                               value="{{ old('nama_pendidikan') }}"
                               autofocus
                               placeholder="Contoh: S1, S2, SMA, D3 ..."
                               class="w-full px-3 py-2.5 text-sm border rounded-xl outline-none transition-shadow
                                      focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                      @error('nama_pendidikan') border-red-400 bg-red-50 @else border-gray-200 @enderror">
                        @error('nama_pendidikan')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('pendidikan.index') }}"
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
