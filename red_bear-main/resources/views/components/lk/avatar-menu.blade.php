@php
    $initials = mb_substr($user?->name ?? '', 0, 1) . mb_substr($user?->surname ?? '', 0, 1);
@endphp

<div class="relative">
    <input id="avatar-menu" type="checkbox" class="peer sr-only">

    <label for="avatar-menu"
           class="flex cursor-pointer items-center gap-3 rounded-full p-1 peer-focus-visible:ring-2 peer-focus-visible:ring-brand-red"
           aria-label="Меню профиля">
        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-red text-sm font-semibold">
            {{ $initials ?: '?' }}
        </span>
        <span class="hidden text-sm font-semibold sm:block">{{ $user?->name . ' ' . $user?->surname }}</span>
        <svg class="h-4 w-4 text-white/55" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
    </label>

    <label for="avatar-menu" class="fixed inset-0 z-40 hidden cursor-default peer-checked:block" aria-hidden="true"></label>

    <div class="absolute right-0 z-50 mt-2 hidden w-64 flex-col rounded-lg border border-white/10 bg-brand-card p-2 shadow-xl peer-checked:flex">
        <div class="flex flex-col px-3 py-2">
            <span class="font-semibold">{{ $user?->name . ' ' . $user?->surname }}</span>
            <span class="text-sm text-white/55">{{ $user->email }}</span>
        </div>
        <div class="my-1 border-t border-white/10"></div>
        <a href="/profile">
            <button type="submit" class="flex w-full items-center gap-3 rounded-md px-3 py-2.5 text-left text-sm font-medium hover:bg-white/5">
                Профиль
            </button>
        </a>
        <a href="/logout">
            <button type="submit" class="flex w-full items-center gap-3 rounded-md px-3 py-2.5 text-left text-sm font-medium text-error hover:bg-white/5">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                Выйти
            </button>
        </a>
    </div>
</div>
