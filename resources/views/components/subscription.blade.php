@props(['user'])

@php
    $price = \App\Console\Commands\ChargeSubscriptions::PRICE;
    $money = fn ($n) => number_format($n, 0, ',', "\u{00A0}");
    $date = fn ($d) => $d->copy()->locale('ru')->translatedFormat('j F');

    $active = $user->hasSubscription();
    $ends = $user->subscription_ends_at;
    $renews = $active && $user->subscription_activated_at !== null;
    $short = $renews ? max(0, $price - (float) $user->balance) : 0;

    $state = ! $active
        ? 'inactive'
        : ($short > 0 ? 'warning' : 'active');

    $daysLeft = $active
        ? max(0, (int) now()->startOfDay()->diffInDays($ends->copy()->startOfDay(), false))
        : 0;

    $periodDays = $active
        ? max(1, (int) $ends->copy()->subMonthNoOverflow()->diffInDays($ends))
        : 30;

    $elapsed = min(100, max(0, 100 - (int) round($daysLeft / $periodDays * 100)));
@endphp

@if($user->role === "user")

    <div data-balance {{ $attributes->merge(['class' => 'relative flex w-full flex-col gap-4 overflow-hidden rounded-2xl border border-white/10 p-4']) }}
        @class([
            'border-plate-green/25 bg-gradient-to-br from-plate-green/[0.09] via-brand-card/70 to-brand-ink' => $state === 'active',
            'border-plate-yellow/30 bg-gradient-to-br from-plate-yellow/[0.08] via-brand-card/70 to-brand-ink' => $state === 'warning',
            'border-brand-chalk/10 bg-brand-card/50' => $state === 'inactive',
        ])>

        @error('subscription')
        <p role="alert" class="flex items-start gap-2 text-sm font-medium text-error">
            <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-4a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 6zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
            </svg>
            <span>{{ $message }}</span>
        </p>
        @enderror

        {{-- ДЕКОРАТИВНОЕ СВЕЧЕНИЕ --}}
        @if ($active)
            <div @class([
                'pointer-events-none absolute -right-16 -top-20 h-48 w-48 rounded-full blur-3xl',
                'bg-plate-green/[0.08]' => $state === 'active',
                'bg-plate-yellow/[0.08]' => $state === 'warning',
            ])></div>
        @endif

        {{-- ЗАГОЛОВОК И СТАТУС --}}
        <div class="relative flex items-center justify-between gap-3">
            <div class="flex min-w-0 items-center gap-3">
                <div @class([
                    'flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border',
                    'border-plate-green/20 bg-plate-green/10 text-plate-green' => $state === 'active',
                    'border-plate-yellow/20 bg-plate-yellow/10 text-plate-yellow' => $state === 'warning',
                    'border-brand-chalk/10 bg-brand-chalk/[0.04] text-brand-chalk/35' => $state === 'inactive',
                ])>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.7"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <path d="M12 3 19 6v5c0 4.5-3 7.5-7 10-4-2.5-7-5.5-7-10V6l7-3Z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                </div>

                <div class="min-w-0">
                    <p class="text-xs font-medium uppercase tracking-[0.14em] text-brand-chalk/40">
                        Мой абонемент
                    </p>

                    <h3 class="mt-1 truncate text-base font-semibold tracking-tight text-brand-chalk">
                        {{ $active ? 'Без ограничений' : 'Доступ в зал' }}
                    </h3>
                </div>
            </div>

            <span @class([
                'inline-flex shrink-0 items-center gap-1.5 rounded-full border px-2.5 py-1.5 text-xs font-medium',
                'border-plate-green/20 bg-plate-green/[0.08] text-plate-green' => $state === 'active',
                'border-plate-yellow/20 bg-plate-yellow/[0.08] text-plate-yellow' => $state === 'warning',
                'border-brand-chalk/10 bg-brand-chalk/[0.04] text-brand-chalk/45' => $state === 'inactive',
            ])>
                <span @class([
                    'h-1.5 w-1.5 rounded-full',
                    'bg-plate-green' => $state === 'active',
                    'bg-plate-yellow' => $state === 'warning',
                    'bg-brand-chalk/30' => $state === 'inactive',
                ])></span>

                {{ $active ? ($state === 'warning' ? 'Внимание' : 'Активен') : 'Не активен' }}
            </span>
        </div>

        @if ($active)

            {{-- ИНФОРМАЦИЯ ОБ АБОНЕМЕНТЕ --}}
            <div class="relative rounded-2xl bg-brand-ink/40 pb-2">
                <p class="text-sm text-brand-chalk/50">
                    {{ $renews ? 'Следующее списание' : 'Абонемент действует до' }}
                </p>

                <div class="mt-2 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <p class="display text-2xl font-bold leading-tight tracking-tight text-brand-chalk sm:text-3xl">
                            {{ $date($ends) }}
                        </p>

                        <p class="mt-2 text-sm text-brand-chalk/45">
                            {{ $daysLeft === 0 ? 'Последний день действия' : "Осталось {$daysLeft} дн." }}
                        </p>
                    </div>

                    @if ($renews)
                        <div class="text-right">
                            <p class="text-xs text-brand-chalk/40">Стоимость месяца</p>

                            <p class="mt-1 text-lg font-semibold tabular-nums text-brand-chalk">
                                {{ $money($price) }}
                                <span class="text-sm font-normal text-brand-chalk/50">₽</span>
                            </p>
                        </div>
                    @else
                        <span class="rounded-full border border-brand-chalk/10 px-3 py-1.5 text-xs text-brand-chalk/50">
                            Без автопродления
                        </span>
                    @endif
                </div>
            </div>

            {{-- ПРОГРЕСС ОПЛАЧЕННОГО ПЕРИОДА --}}
            <div class="relative">
                <div class="mb-2.5 flex items-center justify-between gap-3">
                    <p class="text-sm font-medium text-brand-chalk/75">
                        Оплаченный период
                    </p>

                    <p class="text-xs tabular-nums text-brand-chalk/40">
                        {{ $elapsed }}%
                    </p>
                </div>

                <div class="h-2 w-full overflow-hidden rounded-full bg-brand-chalk/[0.08]"
                     role="progressbar"
                     aria-label="Использовано оплаченного периода"
                     aria-valuemin="0"
                     aria-valuemax="100"
                     aria-valuenow="{{ $elapsed }}">

                    <div @class([
                        'h-full rounded-full transition-all duration-500',
                        'bg-plate-green' => $state === 'active',
                        'bg-plate-yellow' => $state === 'warning',
                    ]) style="width: {{ $elapsed }}%"></div>
                </div>

                <div class="mt-2 flex items-center justify-between gap-3 text-xs text-brand-chalk/35">
                    <span>{{ $date($ends->copy()->subMonthNoOverflow()) }}</span>
                    <span>{{ $date($ends) }}</span>
                </div>
            </div>

            {{-- ПРЕДУПРЕЖДЕНИЕ О БАЛАНСЕ --}}
            @if ($state === 'warning')
                <div role="alert"
                     class="relative flex gap-3 rounded-2xl border border-plate-yellow/20 bg-plate-yellow/[0.06] p-4">

                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-plate-yellow/10 text-plate-yellow">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <path d="M12 9v4"/>
                            <path d="M12 17h.01"/>
                            <path d="M10.3 3.9 2.5 17.4A2 2 0 0 0 4.2 20h15.6a2 2 0 0 0 1.7-2.6L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-plate-yellow">
                            Не хватает {{ $money($short) }} ₽
                        </p>

                        <p class="mt-1 text-sm leading-relaxed text-brand-chalk/60">
                            Пополни баланс до {{ $date($ends) }}, чтобы продлить абонемент автоматически.
                        </p>
                    </div>
                </div>
            @endif

        @else

            {{-- НЕТ АКТИВНОГО АБОНЕМЕНТА --}}
            <div class="relative">
                <p class="text-sm leading-relaxed text-brand-chalk/55">
                    @if ($ends)
                        Твой абонемент закончился {{ $date($ends) }}. Оформи новый, чтобы вернуться к тренировкам.
                    @else
                        Пользуйся свободным доступом в зал и посещай одну бесплатную тренировку каждый месяц.
                    @endif
                </p>
            </div>

            {{-- СТОИМОСТЬ --}}

            <p class="display mt-1 text-2xl font-bold tracking-tight text-brand-chalk sm:text-3xl">
                {{ $money($price) }}
                <span class="text-lg text-brand-chalk/50">₽</span>
            </p>

            {{-- ОФОРМЛЕНИЕ --}}
            <a href="/subscriptions"
               class="group relative inline-flex w-full items-center justify-center gap-2 rounded-full bg-brand-red px-5 py-3 text-sm font-semibold text-brand-chalk transition hover:bg-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-red focus-visible:ring-offset-2 focus-visible:ring-offset-brand-ink">

                Оформить абонемент

                <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5"
                     viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round"
                     aria-hidden="true">
                    <path d="M5 12h14"/>
                    <path d="m12 5 7 7-7 7"/>
                </svg>
            </a>

        @endif
    </div>
@endif

