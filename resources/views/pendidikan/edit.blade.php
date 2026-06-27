<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Jenjang Pendidikan</h2>
            <a href="{{ route('pendidikan.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <form method="POST" action="{{ route('pendidikan.update', $pendidikan) }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Jenjang --}}
                    <div>
                        <label for="jenjang" class="block text-sm font-semibold text-gray-700 mb-1.5">Jenjang <span class="text-red-500">*</span></label>
                        <input type="text" name="jenjang" id="jenjang" required
                               value="{{ old('jenjang', $pendidikan->jenjang) }}"
                               placeholder="Contoh: S1, S2, D3, SMA, Paket C, dll."
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none bg-white @error('jenjang') border-red-400 ring-2 ring-red-100 @enderror">
                        @error('jenjang')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('pendidikan.index') }}"
                           class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm">
                            Perbarui
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
