{{--
    Komponen: <x-searchable-select>
    Props:
      - name        : nama field (untuk form submission)
      - id          : id unik elemen (wajib berbeda tiap pemakaian)
      - placeholder : teks placeholder (default: "— Pilih —")
      - selected    : value yang sudah terpilih (untuk edit, isi old() atau $model->id)
      - error       : boolean, true jika ada error validasi

    Slot: isi dengan <option> tag seperti biasa.

    Contoh pemakaian:
      <x-searchable-select name="id_jabatan" id="ss-jabatan" placeholder="— Pilih Jabatan —"
                           :selected="old('id_jabatan', $karyawan->id_jabatan ?? '')"
                           :error="$errors->has('id_jabatan')">
          @foreach($jabatans as $j)
              <option value="{{ $j->id_jabatan }}">{{ $j->nama_jabatan }}</option>
          @endforeach
      </x-searchable-select>
--}}

@props([
    'name',
    'id',
    'placeholder' => '— Pilih —',
    'selected'    => '',
    'error'       => false,
])

{{--
    Kita parse opsi dari slot menjadi array JS.
    Blade akan render <option> biasa, lalu JS membacanya.
--}}

{{-- Wrapper hidden select — tetap ada agar form submission berjalan normal --}}
<div
    x-data="searchableSelect({
        id:          '{{ $id }}',
        selected:    '{{ $selected }}',
        placeholder: '{{ $placeholder }}',
    })"
    x-init="init()"
    class="relative"
    @click.outside="close()"
>
    {{-- Hidden native select (untuk form submit) --}}
    <select :name="'{{ $name }}'" x-ref="nativeSelect" class="sr-only" aria-hidden="true">
        <option value="">{{ $placeholder }}</option>
        {{ $slot }}
    </select>

    {{-- Tombol trigger --}}
    <button
        type="button"
        @click="toggle()"
        @keydown.escape="close()"
        :class="[
            'w-full flex items-center justify-between px-3 py-2.5 text-sm border rounded-xl bg-white transition-shadow',
            open
                ? 'border-transparent ring-2 ring-blue-500'
                : '{{ $error ? "border-red-400" : "border-gray-200" }} hover:border-gray-300',
        ]"
    >
        <span :class="selectedLabel ? 'text-gray-800' : 'text-gray-400'" x-text="selectedLabel || '{{ $placeholder }}'"></span>
        <svg
            class="w-4 h-4 text-gray-400 transition-transform duration-200 flex-shrink-0 ml-2"
            :class="open ? 'rotate-180' : ''"
            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    {{-- Dropdown panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 -translate-y-1 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 -translate-y-1 scale-95"
        class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden"
        style="display:none"
    >
        {{-- Search input --}}
        <div class="p-2 border-b border-gray-100">
            <div class="relative">
                <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input
                    type="text"
                    x-ref="searchInput"
                    x-model="query"
                    @keydown.escape="close()"
                    @keydown.arrow-down.prevent="focusNext()"
                    @keydown.arrow-up.prevent="focusPrev()"
                    @keydown.enter.prevent="selectFocused()"
                    placeholder="Ketik untuk mencari..."
                    class="w-full pl-7 pr-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
        </div>

        {{-- Option list --}}
        <ul
            x-ref="listbox"
            class="max-h-52 overflow-y-auto py-1"
            role="listbox"
        >
            {{-- Opsi kosong / reset --}}
            <li
                @click="selectOption('', '{{ $placeholder }}')"
                @mouseenter="focusedIndex = -1"
                :class="selectedValue === '' ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-400'"
                class="px-3 py-2 text-sm cursor-pointer hover:bg-gray-50 transition-colors"
            >
                {{ $placeholder }}
            </li>

            {{-- Opsi dari data --}}
            <template x-for="(opt, idx) in filteredOptions" :key="opt.value">
                <li
                    @click="selectOption(opt.value, opt.label)"
                    @mouseenter="focusedIndex = idx"
                    :class="{
                        'bg-blue-50 text-blue-700 font-medium': selectedValue === opt.value,
                        'bg-gray-100': focusedIndex === idx && selectedValue !== opt.value,
                        'text-gray-700': selectedValue !== opt.value,
                    }"
                    class="px-3 py-2 text-sm cursor-pointer hover:bg-blue-50 hover:text-blue-700 transition-colors"
                    role="option"
                    :aria-selected="selectedValue === opt.value"
                    x-text="opt.label"
                ></li>
            </template>

            {{-- Tidak ada hasil --}}
            <li x-show="filteredOptions.length === 0"
                class="px-3 py-4 text-sm text-gray-400 text-center">
                Tidak ada hasil untuk "<span x-text="query"></span>"
            </li>
        </ul>
    </div>
</div>

@once
@push('scripts')
<script>
function searchableSelect({ id, selected, placeholder }) {
    return {
        open:          false,
        query:         '',
        selectedValue: selected ?? '',
        selectedLabel: '',
        focusedIndex:  -1,
        options:       [],

        // ── Init: baca opsi dari native select ──────────────────────────
        init() {
            const sel = this.$refs.nativeSelect;
            this.options = Array.from(sel.options)
                .filter(o => o.value !== '')          // skip placeholder option
                .map(o => ({ value: o.value, label: o.text.trim() }));

            // Set label awal jika ada nilai terpilih
            if (this.selectedValue) {
                const found = this.options.find(o => o.value == this.selectedValue);
                if (found) this.selectedLabel = found.label;
            }

            // Sync ke native select agar value awal benar
            this.syncNative();
        },

        // ── Computed: opsi yang difilter berdasarkan query ───────────────
        get filteredOptions() {
            if (!this.query) return this.options;
            const q = this.query.toLowerCase();
            return this.options.filter(o => o.label.toLowerCase().includes(q));
        },

        // ── Toggle buka/tutup ────────────────────────────────────────────
        toggle() {
            this.open ? this.close() : this.openDropdown();
        },

        openDropdown() {
            this.open  = true;
            this.query = '';
            this.focusedIndex = -1;
            this.$nextTick(() => this.$refs.searchInput?.focus());
        },

        close() {
            this.open  = false;
            this.query = '';
            this.focusedIndex = -1;
        },

        // ── Pilih opsi ───────────────────────────────────────────────────
        selectOption(value, label) {
            this.selectedValue = value;
            this.selectedLabel = value ? label : '';
            this.syncNative();
            this.close();
        },

        // ── Sync value ke native select (untuk form submit) ──────────────
        syncNative() {
            const sel = this.$refs.nativeSelect;
            if (!sel) return;
            sel.value = this.selectedValue;
            // Dispatch change event agar validasi Alpine/Vue mendeteksi
            sel.dispatchEvent(new Event('change', { bubbles: true }));
        },

        // ── Navigasi keyboard ────────────────────────────────────────────
        focusNext() {
            if (this.focusedIndex < this.filteredOptions.length - 1) {
                this.focusedIndex++;
            }
        },
        focusPrev() {
            if (this.focusedIndex > 0) this.focusedIndex--;
        },
        selectFocused() {
            if (this.focusedIndex >= 0 && this.filteredOptions[this.focusedIndex]) {
                const opt = this.filteredOptions[this.focusedIndex];
                this.selectOption(opt.value, opt.label);
            }
        },
    };
}
</script>
@endpush
@endonce
