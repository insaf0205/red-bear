@props(['name', 'label', 'type' => 'text', 'value' => '', 'autocomplete' => null])

<div class="flex flex-1 flex-col gap-2">
    <label for="{{ $name }}" class="text-sm font-semibold">{{ $label }}</label>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}"
           value="{{ old($name, $value) }}"
           @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
           @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror
           class="rounded-lg border border-white/15 bg-brand-card px-4 py-3 text-base placeholder:text-white/30 focus:border-brand-red focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-red aria-invalid:border-error">
    @error($name)
    <p id="{{ $name }}-error" role="alert" class="flex items-start gap-2 text-sm font-medium text-error">
        <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-4a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 6zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
        </svg>
        <span>{{ $message }}</span>
    </p>
    @enderror
</div>
