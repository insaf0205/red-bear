@props(["user", 'balance' => 0])

@php
    // 1200 -> «1 200», 1200.5 -> «1 200,50»; неразрывный пробел, чтобы сумма не переносилась
    $decimals = floor($balance) == $balance ? 0 : 2;
    $amount = number_format($balance, $decimals, ',', "\u{00A0}");

    // после ошибки валидации остаёмся в режиме ввода суммы
    $open = $errors->has('message');

    $inputId = 'topup-sum-' . \Illuminate\Support\Str::random(5);
@endphp

@if($user->role === "user")

    <div data-balance {{ $attributes->merge(['class' => 'flex w-full flex-col gap-4 rounded-2xl border border-white/10 p-4']) }}>

        {{-- БАЛАНС --}}
        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-2 text-xs text-white/55">
                {{-- маленький блин вместо монеты --}}
                Баланс
            </div>
            <span class="display text-[26px] leading-none tabular-nums">{{ $amount }}&nbsp;₽</span>
        </div>

        {{-- шаг 1: кнопка «Пополнить счет» --}}
        <button type="button" data-balance-open data-balance-button
            @class([
                'inline-flex w-full items-center justify-center gap-2 rounded-full bg-brand-red px-4 py-2.5 text-sm font-semibold hover:bg-brand-dark',
                'hidden' => $open,
            ])>
            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Пополнить счет
        </button>

        {{-- шаг 2: сумма и кнопка «Пополнить» --}}
        <form method="POST" action="/payments" data-balance-form
            @class(['flex flex-col gap-3', 'hidden' => ! $open])>
            @csrf

            <div class="flex flex-col gap-2">
                <label for="{{ $inputId }}" class="text-sm font-semibold">Сумма пополнения</label>
                <div class="relative">
                    <input id="{{ $inputId }}" name="sum" type="number" inputmode="numeric" min="100" step="1" required
                           placeholder="От 100"
                           class="w-full rounded-xl border border-white/15 bg-brand-ink py-3 pl-4 pr-10 text-lg tabular-nums placeholder:text-white/30 focus:border-brand-red focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-red [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none">
                    <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-white/45">₽</span>
                </div>
            </div>

            {{-- быстрый выбор суммы --}}
            <div class="flex flex-wrap gap-2">
                @foreach ([500, 1000, 2000, 5000] as $preset)
                    <button type="button" data-balance-preset="{{ $preset }}" aria-pressed="false"
                            class="rounded-full border border-white/15 px-3 py-1 text-xs font-medium tabular-nums text-white/70 hover:border-white/40 hover:text-white aria-pressed:border-brand-red aria-pressed:text-white">
                        {{ number_format($preset, 0, ',', "\u{00A0}") }}&nbsp;₽
                    </button>
                @endforeach
            </div>

            <p class="text-xs text-white/45">Минимальная сумма — 100&nbsp;₽</p>

            @error('message')
            <p role="alert" class="flex items-start gap-2 text-sm font-medium text-error">
                <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-4a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 6zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                </svg>
                <span>{{ $message }}</span>
            </p>
            @enderror

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 rounded-full bg-brand-red px-4 py-2.5 text-sm font-semibold hover:bg-brand-dark disabled:opacity-60">
                    Пополнить
                </button>
                <button type="button" data-balance-close aria-label="Отмена"
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-white/15 text-white/60 hover:border-white/40 hover:text-white">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18"/>
                    </svg>
                </button>
            </div>
        </form>

    </div>

    @once
        <script>
            (function () {
                function input(root) { return root.querySelector('input[name="sum"]'); }

                // подсвечиваем быструю сумму, если она совпадает с введённой
                function sync(root) {
                    var value = input(root).value;
                    root.querySelectorAll('[data-balance-preset]').forEach(function (chip) {
                        chip.setAttribute('aria-pressed', chip.dataset.balancePreset === value ? 'true' : 'false');
                    });
                }

                function toggle(root, open) {
                    root.querySelector('[data-balance-button]').classList.toggle('hidden', open);
                    root.querySelector('[data-balance-form]').classList.toggle('hidden', !open);
                    if (open) input(root).focus();
                    else root.querySelector('[data-balance-button]').focus();
                }

                document.addEventListener('click', function (e) {
                    var root = e.target.closest('[data-balance]');
                    if (!root) return;

                    var preset = e.target.closest('[data-balance-preset]');
                    if (preset) {
                        input(root).value = preset.dataset.balancePreset;
                        sync(root);
                        input(root).focus();
                        return;
                    }

                    if (e.target.closest('[data-balance-open]')) toggle(root, true);
                    else if (e.target.closest('[data-balance-close]')) toggle(root, false);
                });

                document.addEventListener('input', function (e) {
                    var root = e.target.closest && e.target.closest('[data-balance]');
                    if (root && e.target.name === 'sum') sync(root);
                });

                document.addEventListener('keydown', function (e) {
                    if (e.key !== 'Escape') return;
                    var root = e.target.closest && e.target.closest('[data-balance]');
                    if (root && e.target.closest('[data-balance-form]')) toggle(root, false);
                });

                // защита от двойного платежа при повторном нажатии
                document.addEventListener('submit', function (e) {
                    var form = e.target.closest && e.target.closest('[data-balance-form]');
                    if (!form) return;
                    var button = form.querySelector('button[type="submit"]');
                    button.disabled = true;
                    button.textContent = 'Переходим к оплате…';
                });
            })();
        </script>
    @endonce
@endif

