@props(['label', 'value' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-1 border-t border-white/15 py-4 sm:flex-row sm:gap-6']) }}>
    <dt class="text-sm text-white/55 sm:w-48 sm:shrink-0">{{ $label }}</dt>
    <dd class="font-medium">{{ $value ?? $slot }}</dd>
</div>
