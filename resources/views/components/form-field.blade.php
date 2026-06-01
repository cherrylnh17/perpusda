@props(['label', 'name', 'required' => false])

<div>
    <label for="{{ $name }}" class="block text-xs font-medium text-gray-600 mb-1.5">
        {{ $label }}
        @if ($required)
            <span class="text-red-500 ml-0.5">*</span>
        @endif
    </label>

    {{ $slot }}

    @error($name)
        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>
