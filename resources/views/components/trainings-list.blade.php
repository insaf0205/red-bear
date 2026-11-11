@props(['trainings', "user"])

<div class="space-y-4">
    <div class="flex flex-col gap-6">
        @forelse($trainings as $train)
            <x-train :train="$train" :user="$user" />
        @empty
            <p class="text-white/40">Нет</p>
        @endforelse
    </div>

    @if ($trainings->hasPages())
        <div class="mt-2">
            {{ $trainings->links('vendor.pagination.custom') }}
        </div>
    @endif
</div>
