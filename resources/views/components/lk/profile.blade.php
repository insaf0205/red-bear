@props(['user'])

<section id="data" class="group flex flex-wrap items-baseline justify-between gap-x-4 gap-y-6">

    <input id="edit-profile" type="checkbox" class="peer sr-only" {{ $errors->any() ? 'checked' : '' }}>

    <h2 class="text-xl font-semibold">Личные данные</h2>

    <label for="edit-profile"
           class="cursor-pointer text-sm font-semibold text-white/70 underline underline-offset-4 hover:text-brand-red peer-focus-visible:text-brand-red group-has-[:checked]:hidden">
        Изменить
    </label>
    <label for="edit-profile"
           class="hidden cursor-pointer text-sm font-semibold text-white/70 underline underline-offset-4 hover:text-brand-red group-has-[:checked]:inline">
        Отмена
    </label>

    {{-- РЕЖИМ ПРОСМОТРА --}}
    <dl class="flex w-full flex-col group-has-[:checked]:hidden">
        <x-lk.row label="Имя" :value="$user->name" />
        <x-lk.row label="Фамилия" :value="$user->surname" />
        <x-lk.row label="Email" :value="$user->email" />
        <x-lk.row label="Телефон" :value="$user->phone" />

        @if($user->role === 'admin')
            <x-lk.row label="Роль" :value="$user->role" />
        @endif

        <x-lk.row label="Дата регистрации" :value="$user->created_at->format('d.m.Y')"
                  class="border-y border-white/15" />
    </dl>

    {{-- РЕЖИМ РЕДАКТИРОВАНИЯ --}}
    <form method="POST" action="/profile" class="hidden w-full flex-col gap-6 border-t border-white/15 pt-6 group-has-[:checked]:flex">
        @csrf
        @method('PATCH')

        <div class="flex flex-col gap-6 sm:flex-row">
            <x-field name="name" label="Имя" :value="$user->name" autocomplete="given-name" />
            <x-field name="surname" label="Фамилия" :value="$user->surname" autocomplete="family-name" />
        </div>
        <div class="flex flex-col gap-6 sm:flex-row">
            <x-field name="email" label="Email" type="email" :value="$user->email" autocomplete="email" />
            <x-field name="phone" label="Телефон" type="tel" :value="$user->phone" autocomplete="tel" />
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <button type="submit" class="rounded-full bg-brand-red px-8 py-3 font-semibold hover:bg-brand-dark">
                Сохранить
            </button>
            <label for="edit-profile" class="cursor-pointer rounded-full border border-white/30 px-8 py-3 font-semibold hover:border-white">
                Отмена
            </label>
        </div>
    </form>

</section>
