<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('karyawan.index') }}"
               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-lg text-gray-900 leading-tight">Tambah Karyawan</h2>
                <p class="text-xs text-gray-400 mt-0.5">Lengkapi semua data yang diperlukan</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Step Indicator --}}
            <div class="hidden sm:flex items-center mb-6">
                <div class="flex items-center gap-2 text-xs font-semibold text-blue-600">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">1</span>
                    Foto Profil
                </div>
                <div class="flex-1 h-px bg-blue-200 mx-3"></div>
                <div class="flex items-center gap-2 text-xs font-semibold text-blue-600">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">2</span>
                    Data Pribadi
                </div>
                <div class="flex-1 h-px bg-blue-200 mx-3"></div>
                <div class="flex items-center gap-2 text-xs font-semibold text-blue-600">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">3</span>
                    Kepegawaian
                </div>
            </div>

            <form action="{{ route('karyawan.store') }}" method="POST"
                  enctype="multipart/form-data"
                  x-data="fotoPreview()">
                @csrf

                <div class="space-y-4">

                    {{-- 1. Foto Profil --}}
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-5 py-3.5 bg-gray-50 border-b border-gray-200">
                            <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Foto Profil</span>
                        </div>
                        <div class="p-5 flex items-center gap-5">
                            {{-- Preview --}}
                            <div class="flex-shrink-0 w-20 h-20 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden">
                                <template x-if="preview">
                                    <img :src="preview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!preview">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 3h18M3 3v18M21 3v18"/>
                                    </svg>
                                </template>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="cursor-pointer inline-flex items-center gap-2 px-3.5 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg border border-blue-200 transition-colors w-fit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                                    </svg>
                                    Pilih Foto
                                    <input type="file" name="foto" accept="image/*" class="hidden" @change="onFileChange($event)">
                                </label>
                                <p class="text-xs text-gray-400">JPG, PNG, WEBP &middot; Maks 2 MB</p>
                                @error('foto')<p class="text-xs text-red-500 mt-0.5">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- 2. Data Pribadi --}}
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-5 py-3.5 bg-gray-50 border-b border-gray-200">
                            <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Data Pribadi</span>
                        </div>
                        <div class="p-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                                           placeholder="Nama sesuai KTP"
                                           class="w-full px-3.5 py-2.5 text-sm border rounded-xl bg-white text-gray-900 outline-none transition-all focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400 @error('nama_lengkap') border-red-400 ring-2 ring-red-100 @else border-gray-300 @enderror">
                                    @error('nama_lengkap')
                                        <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                            <span class="w-1 h-1 rounded-full bg-red-500 flex-shrink-0 inline-block"></span>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                        NIK <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nik" value="{{ old('nik') }}" maxlength="20"
                                           placeholder="16 digit NIK" inputmode="numeric"
                                           class="w-full px-3.5 py-2.5 text-sm border rounded-xl bg-white text-gray-900 outline-none transition-all focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400 @error('nik') border-red-400 ring-2 ring-red-100 @else border-gray-300 @enderror">
                                    @error('nik')
                                        <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                            <span class="w-1 h-1 rounded-full bg-red-500 flex-shrink-0 inline-block"></span>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                        NIP <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nip" value="{{ old('nip') }}" maxlength="50"
                                           placeholder="Nomor Induk Pegawai"
                                           class="w-full px-3.5 py-2.5 text-sm border rounded-xl bg-white text-gray-900 outline-none transition-all focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400 @error('nip') border-red-400 ring-2 ring-red-100 @else border-gray-300 @enderror">
                                    @error('nip')
                                        <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                            <span class="w-1 h-1 rounded-full bg-red-500 flex-shrink-0 inline-block"></span>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Jenis Kelamin --}}
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jenis Kelamin</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach(['Laki-laki' => '♂', 'Perempuan' => '♀'] as $jk => $icon)
                                            <label class="relative cursor-pointer">
                                                <input type="radio" name="jenis_kelamin" value="{{ $jk }}"
                                                       {{ old('jenis_kelamin') == $jk ? 'checked' : '' }}
                                                       class="peer sr-only">
                                                <span class="flex items-center justify-center gap-1.5 h-10 text-xs font-semibold border-2 border-gray-200 rounded-xl text-gray-500 transition-all cursor-pointer
                                                    peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700
                                                    hover:border-blue-300 hover:text-blue-600">
                                                    {{ $icon }} {{ $jk }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('jenis_kelamin')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Tanggal Lahir --}}
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                           class="w-full px-3.5 py-2.5 text-sm border rounded-xl bg-white text-gray-900 outline-none transition-all focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal_lahir') border-red-400 ring-2 ring-red-100 @else border-gray-300 @enderror">
                                    @error('tanggal_lahir')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Agama</label>
                                    <select name="agama"
                                            class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-xl bg-white text-gray-900 outline-none transition-all focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">— Pilih Agama —</option>
                                        @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $ag)
                                            <option value="{{ $ag }}" {{ old('agama') == $ag ? 'selected' : '' }}>{{ $ag }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Golongan Darah</label>
                                    <div class="grid grid-cols-4 gap-2">
                                        @foreach(['A','B','AB','O'] as $gd)
                                            <label class="relative cursor-pointer">
                                                <input type="radio" name="golongan_darah" value="{{ $gd }}"
                                                       {{ old('golongan_darah') == $gd ? 'checked' : '' }}
                                                       class="peer sr-only">
                                                <span class="flex items-center justify-center h-10 text-xs font-bold border-2 border-gray-200 rounded-xl text-gray-500 transition-all cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:border-blue-300 hover:text-blue-600">
                                                    {{ $gd }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Alamat</label>
                                    <textarea name="alamat" rows="3"
                                              placeholder="Alamat lengkap sesuai KTP"
                                              class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-xl bg-white text-gray-900 outline-none resize-none transition-all focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400 @error('alamat') border-red-400 ring-2 ring-red-100 @enderror">{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- 3. Data Kepegawaian --}}
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-5 py-3.5 bg-gray-50 border-b border-gray-200">
                            <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Data Kepegawaian</span>
                        </div>
                        <div class="p-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jabatan</label>
                                    <x-searchable-select
                                        name="id_jabatan" id="ss-jabatan"
                                        placeholder="— Pilih Jabatan —"
                                        :selected="old('id_jabatan', '')"
                                        :error="$errors->has('id_jabatan')">
                                        @foreach ($jabatans as $j)
                                            <option value="{{ $j->id_jabatan }}">{{ $j->nama_jabatan }}</option>
                                        @endforeach
                                    </x-searchable-select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Pendidikan</label>
                                    <x-searchable-select
                                        name="id_pendidikan" id="ss-pendidikan"
                                        placeholder="— Pilih Pendidikan —"
                                        :selected="old('id_pendidikan', '')"
                                        :error="$errors->has('id_pendidikan')">
                                        @foreach ($pendidikans as $p)
                                            <option value="{{ $p->id_pendidikan }}">{{ $p->nama_pendidikan }}</option>
                                        @endforeach
                                    </x-searchable-select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jenis Kontrak</label>
                                    <x-searchable-select
                                        name="id_jenis_kontrak" id="ss-kontrak"
                                        placeholder="— Pilih Jenis Kontrak —"
                                        :selected="old('id_jenis_kontrak', '')"
                                        :error="$errors->has('id_jenis_kontrak')">
                                        @foreach ($kontraks as $kt)
                                            <option value="{{ $kt->id_jenis_kontrak }}">{{ $kt->nama_kontrak }}</option>
                                        @endforeach
                                    </x-searchable-select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                        Status Kepegawaian <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-2 gap-2">
                                        @php
                                            $statuses = [
                                                'Aktif'   => 'bg-emerald-50 border-emerald-400 text-emerald-700',
                                                'Cuti'    => 'bg-amber-50 border-amber-400 text-amber-700',
                                                'Pensiun' => 'bg-violet-50 border-violet-400 text-violet-700',
                                                'Resign'  => 'bg-red-50 border-red-400 text-red-700',
                                            ];
                                            $statusDots = [
                                                'Aktif' => 'bg-emerald-500', 'Cuti' => 'bg-amber-500',
                                                'Pensiun' => 'bg-violet-500', 'Resign' => 'bg-red-500',
                                            ];
                                        @endphp
                                        @foreach($statuses as $s => $checkedClass)
                                            <label class="relative cursor-pointer">
                                                <input type="radio" name="status_aktif" value="{{ $s }}"
                                                       {{ old('status_aktif', 'Aktif') == $s ? 'checked' : '' }}
                                                       class="peer sr-only">
                                                <span class="flex items-center justify-center gap-1.5 py-2.5 text-xs font-semibold border-2 border-gray-200 rounded-xl text-gray-500 transition-all cursor-pointer hover:border-gray-300 peer-checked:{{ $checkedClass }}">
                                                    <span class="w-2 h-2 rounded-full {{ $statusDots[$s] }}"></span>
                                                    {{ $s }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('status_aktif')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                        Tanggal Masuk <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}"
                                           class="w-full px-3.5 py-2.5 text-sm border rounded-xl bg-white text-gray-900 outline-none transition-all focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal_masuk') border-red-400 ring-2 ring-red-100 @else border-gray-300 @enderror">
                                    @error('tanggal_masuk')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                        Tanggal Mulai Jabatan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="tanggal_mulai_jabatan" value="{{ old('tanggal_mulai_jabatan') }}"
                                           class="w-full px-3.5 py-2.5 text-sm border rounded-xl bg-white text-gray-900 outline-none transition-all focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal_mulai_jabatan') border-red-400 ring-2 ring-red-100 @else border-gray-300 @enderror">
                                    @error('tanggal_mulai_jabatan')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                        Gaji <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-semibold text-gray-400 pointer-events-none">Rp</span>
                                        <input type="number" name="gaji" min="0" step="1000"
                                               value="{{ old('gaji', 0) }}"
                                               class="w-full pl-9 pr-3.5 py-2.5 text-sm border rounded-xl bg-white text-gray-900 outline-none transition-all focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gaji') border-red-400 ring-2 ring-red-100 @else border-gray-300 @enderror">
                                    </div>
                                    @error('gaji')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pb-2">
                        <p class="text-xs text-gray-400 text-center sm:text-left">
                            Kolom bertanda <span class="text-red-500 font-semibold">*</span> wajib diisi
                        </p>
                        <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center gap-2 sm:gap-2.5">
                            <a href="{{ route('karyawan.index') }}"
                               class="flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors border border-gray-200">
                                Batal
                            </a>
                            <button type="submit"
                                    class="flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm shadow-blue-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0115.186 0z"/>
                                </svg>
                                Simpan Karyawan
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    function fotoPreview() {
        return {
            preview: null,
            onFileChange(e) {
                const file = e.target.files[0];
                if (!file) { this.preview = null; return; }
                const reader = new FileReader();
                reader.onload = (ev) => this.preview = ev.target.result;
                reader.readAsDataURL(file);
            }
        };
    }
    </script>
    @endpush

</x-app-layout>
