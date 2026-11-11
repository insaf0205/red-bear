@extends('layouts.lk')

@section("lk-content")

    <div class="flex flex-col gap-14">

        <h1 class="display text-3xl md:text-4xl">Купить абонемент</h1>

        {{-- ЦЕНА И ПОКУПКА --}}
        <section class="flex flex-col gap-12 lg:flex-row lg:items-center lg:justify-between">

            <div class="flex flex-col gap-6">
                <p class="flex flex-wrap items-baseline gap-x-3">
                    <span class="display text-6xl leading-none md:text-7xl">1&nbsp;400&nbsp;₽</span>
                    <span class="text-lg text-white/60">в месяц</span>
                </p>

                <p class="max-w-md text-lg leading-relaxed text-white/70">
                    Один абонемент даёт свободный доступ в зал и одну бесплатную тренировку каждый месяц.
                </p>

                <form method="POST" action="/subscriptions" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-6">
                    @csrf
                    <button type="submit" class="rounded-full bg-brand-red px-10 py-4 font-semibold hover:bg-brand-dark">
                        Купить абонемент
                    </button>
                </form>
            </div>


            {{-- блин как знак абонемента --}}
            <svg viewBox="0 0 600 600" role="img" aria-label="Красный блин для штанги"
                 class="w-56 shrink-0 self-center -rotate-6 drop-shadow-[0_12px_24px_rgba(0,0,0,0.45)] sm:w-64 lg:w-72">
                <defs>
                    <path id="rim-sub" d="M300,300 m-228,0 a228,228 0 1,1 456,0 a228,228 0 1,1 -456,0"/>
                </defs>
                <circle cx="300" cy="300" r="296" fill="#d6202b"/>
                <circle cx="300" cy="300" r="270" fill="none" stroke="#8f121a" stroke-width="6"/>
                <circle cx="300" cy="300" r="190" fill="none" stroke="#8f121a" stroke-width="4"/>
                <circle cx="300" cy="300" r="120" fill="#b01922"/>
                <circle cx="300" cy="300" r="62" fill="#1c1d1f"/>
                <circle cx="300" cy="300" r="26" fill="#8d9096"/>
                <text font-family="Unbounded, sans-serif" font-weight="800" font-size="34" fill="#f1f1ed">
                    <textPath href="#rim-sub" textLength="1432" lengthAdjust="spacing">RED BEAR&#160;&#160;&#160;&#160;АБОНЕМЕНТ&#160;&#160;&#160;&#160;1 МЕСЯЦ&#160;&#160;&#160;&#160;</textPath>
                </text>
            </svg>

        </section>


        {{-- ЧТО ВХОДИТ --}}
        <section class="flex flex-col gap-6">
            <h2 class="text-xl font-semibold">Что входит</h2>

            <dl class="flex flex-col">
                <div class="flex flex-col gap-2 border-t border-white/15 py-7 sm:flex-row sm:items-baseline sm:gap-10">
                    <dt class="display text-4xl sm:w-60 sm:shrink-0">1</dt>
                    <dd class="flex flex-col gap-1">
                        <span class="text-lg font-semibold">Бесплатная тренировки</span>
                        <span class="max-w-md leading-relaxed text-white/65">Входят в стоимость абонемента, платить за нее отдельно не нужно.</span>
                    </dd>
                </div>

                <div class="flex flex-col gap-2 border-t border-white/15 py-7 sm:flex-row sm:items-baseline sm:gap-10">
                    <dt class="display text-4xl sm:w-60 sm:shrink-0">1&nbsp;400&nbsp;₽</dt>
                    <dd class="flex flex-col gap-1">
                        <span class="text-lg font-semibold">Одна цена за месяц</span>
                        <span class="max-w-md leading-relaxed text-white/65">Стоимость абонемента фиксированная, считать ничего не нужно.</span>
                    </dd>
                </div>

                <div class="flex flex-col gap-2 border-y border-white/15 py-7 sm:flex-row sm:items-baseline sm:gap-10">
                    <dt class="display text-4xl sm:w-60 sm:shrink-0">6:00–2:00</dt>
                    <dd class="flex flex-col gap-1">
                        <span class="text-lg font-semibold">Зал в любое время</span>
                        <span class="max-w-md leading-relaxed text-white/65">Посещение зала бесплатно в любое время с 6:00 до 2:00. Приходи до работы или после неё.</span>
                    </dd>
                </div>
            </dl>
        </section>

        <x-subscription :user="$user" />

        <x-balance :user="$user" :balance="$user->balance" />


    </div>

@endsection
