@props(['train', 'user'])

@php
    $start = \Carbon\Carbon::parse($train->start_at);
    $end = \Carbon\Carbon::parse($train->end_at);

    $isPast = $end->isPast();

    $trainer = $train->trainer;

    $trainerName = $trainer
        ? trim($trainer->name . ' ' . $trainer->surname)
        : 'Тренер не указан';

    $isBooked = (string) $train->student?->id === (string) $user->id;

    // Отзывы о тренере.
    $reviewsCount = (int) ($trainer?->reviews()->count() ?? 0);
    $avgRating = (float) ($trainer?->reviews()->avg('rating') ?? 0);

    $plural = fn (int $n, array $f) => $n % 10 === 1 && $n % 100 !== 11
        ? $f[0]
        : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20)
            ? $f[1]
            : $f[2]);

    $initials = mb_strtoupper(
        mb_substr($trainer?->name ?? '', 0, 1)
        . mb_substr($trainer?->surname ?? '', 0, 1)
    );

    // Один отзыв от пользователя на одного тренера.
    // Связь reviewsGiven должна возвращать отзывы, где user_id — текущий пользователь.
    $alreadyReviewed = $trainer && $user->role === 'user'
        ? $user->loadMissing('reviewsGiven')
            ->reviewsGiven
            ->contains('trainer_id', $trainer->id)
        : false;

    $canEdit = $user->role === 'admin';

    $canReview = $isPast
        && $isBooked
        && $user->role === 'user'
        && ! $alreadyReviewed;

    $startsIn = $start->isFuture()
        ? $start->diffForHumans(now(), [
            'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
            'parts' => 2,
            'short' => true,
        ])
        : null;

    $canBook = $user->role === 'user'
        && ! $isBooked
        && ! $isPast;

    // Месяц в родительном падеже: «12 октября».
    $month = \Illuminate\Support\Str::after(
        $start->translatedFormat('d F'),
        ' '
    );

    $canCancel = $isBooked && ! $isPast;

    // Стоимость.
    $cost = (float) ($train->cost ?? 0);
    $isFree = $cost <= 0;
    $costText = number_format(
        $cost,
        floor($cost) == $cost ? 0 : 2,
        ',',
        "\u{00A0}"
    );

    // После ошибки валидации открываем форму только нужной тренировки.
    $mine = (string) old('training_id') === (string) $train->id;
    $reopen = $mine && $errors->hasAny(['rating', 'comment']);
@endphp

<article @class([
    'group flex flex-col gap-5 rounded-2xl border p-4 transition duration-300 sm:p-5',
    'border-white/10 bg-white/[0.025] hover:border-white/20 hover:bg-white/[0.04]' => ! $isPast,
    'border-white/[0.07] bg-white/[0.015]' => $isPast,
])>
    <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:gap-6">

        {{-- ФОТО --}}
        <div class="order-first w-full shrink-0 sm:order-last sm:w-44">
            <img
                src="{{ $train->image_url ?: asset('default.png') }}"
                alt="{{ $train->name ?: 'Тренировка' }}"
                loading="lazy"
                class="aspect-[16/9] w-full rounded-lg object-cover sm:aspect-[4/3]"
            >
        </div>

        {{-- ДАТА --}}
        <div class="flex items-baseline gap-3 sm:w-28 sm:shrink-0 sm:flex-col sm:gap-1">
            <span @class([
                'display text-5xl leading-none',
                'text-white' => ! $isPast,
                'text-white/35' => $isPast,
            ])>{{ $start->format('d') }}</span>

            <span @class([
                'text-sm',
                'text-white/70' => ! $isPast,
                'text-white/40' => $isPast,
            ])>
                {{ $month }}, {{ $start->translatedFormat('D') }}
            </span>
        </div>

        {{-- ИНФОРМАЦИЯ --}}
        <div class="flex min-w-0 flex-1 flex-col gap-3">

            <p class="flex flex-wrap items-center gap-x-3 gap-y-1 text-sm">
                <span @class([
                    'inline-flex items-center gap-2 font-semibold',
                    'text-[#75d99a]' => ! $isPast,
                    'text-white/40' => $isPast,
                ])>
                    <span @class([
                        'h-2 w-2 rounded-full',
                        'bg-[#5fd08a]' => ! $isPast,
                        'bg-white/30' => $isPast,
                    ])></span>

                    {{ $isPast ? 'Завершена' : 'Запланирована' }}
                </span>

                @if ($startsIn)
                    <span class="text-white/45">
                        Начнётся через {{ $startsIn }}
                    </span>
                @endif
            </p>

            <h3 @class([
                'break-words text-xl font-semibold leading-snug',
                'text-white' => ! $isPast,
                'text-white/50' => $isPast,
            ])>
                {{ $train->name ?: 'Индивидуальная тренировка' }}
            </h3>

            {{-- ТРЕНЕР И РЕЙТИНГ --}}
            <div class="flex items-center gap-3">
                @if ($trainer?->avatar_url)
                    <img
                        src="{{ $trainer->avatar_url }}"
                        alt=""
                        @class([
                            'h-10 w-10 shrink-0 rounded-full object-cover',
                            'opacity-60' => $isPast,
                        ])
                    >
                @else
                    <span @class([
                        'flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-sm font-semibold',
                        'bg-brand-red text-white' => ! $isPast,
                        'bg-white/10 text-white/50' => $isPast,
                    ]) aria-hidden="true">
                        {{ $initials ?: '?' }}
                    </span>
                @endif

                <div class="flex min-w-0 flex-col gap-0.5 leading-tight">
                    <span @class([
                        'truncate text-sm font-semibold',
                        'text-white' => ! $isPast,
                        'text-white/50' => $isPast,
                    ])>
                        {{ $trainerName }}
                    </span>

                    <span class="inline-flex items-center gap-1.5 text-xs text-white/55">
                        @if ($reviewsCount > 0)
                            <svg class="h-3.5 w-3.5 shrink-0 text-plate-yellow"
                                 viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"/>
                            </svg>

                            <span class="font-semibold tabular-nums text-white/80">
                                {{ number_format($avgRating, 1, ',', '') }}
                            </span>

                            <span>
                                {{ $reviewsCount }}
                                {{ $plural($reviewsCount, ['отзыв', 'отзыва', 'отзывов']) }}
                            </span>
                        @else
                            Пока нет отзывов
                        @endif
                    </span>
                </div>
            </div>

            {{-- ВРЕМЯ И СТОИМОСТЬ --}}
            <div @class([
                'flex flex-wrap gap-x-6 gap-y-2 text-sm',
                'text-white/65' => ! $isPast,
                'text-white/40' => $isPast,
            ])>
                <span class="inline-flex items-center gap-2 tabular-nums">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <circle cx="12" cy="12" r="8.5"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                    </svg>

                    {{ $start->format('H:i') }}–{{ $end->format('H:i') }}
                </span>

                <span @class([
                    'inline-flex items-center gap-2 font-semibold tabular-nums',
                    'text-[#75d99a]' => $isFree && ! $isPast,
                    'text-white' => ! $isFree && ! $isPast,
                    'text-white/40' => $isPast,
                ])>
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                    </svg>

                    {{ $isFree ? 'Бесплатно' : $costText . "\u{00A0}₽" }}
                </span>
            </div>

            @if ($train->description)
                <p @class([
                    'max-w-xl break-words text-sm leading-relaxed',
                    'text-white/55' => ! $isPast,
                    'text-white/35' => $isPast,
                ])>
                    {{ $train->description }}
                </p>
            @endif

            @if ($train->student_id && $user->role === 'admin')
                <p class="text-sm text-white/45">
                    Ученик: {{ $train->student?->name ?? '—' }}
                </p>
            @endif

            {{-- ДЕЙСТВИЯ --}}
            @if ($canReview || $canEdit || $isBooked || $canBook)
                <div class="mt-2 flex flex-wrap items-center gap-x-6 gap-y-3">

                    @if ($canReview)
                        <label
                            for="review-toggle-{{ $train->id }}"
                            class="inline-flex cursor-pointer items-center gap-2 rounded text-sm font-semibold underline underline-offset-4 hover:text-brand-red group-has-[.review-toggle:focus-visible]:ring-2 group-has-[.review-toggle:focus-visible]:ring-brand-red"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m12 3 2.7 5.47 6.03.88-4.36 4.25 1.03 6.01L12 16.77l-5.4 2.84 1.03-6.01-4.36-4.25 6.03-.88L12 3z"/>
                            </svg>

                            <span class="group-has-[.review-toggle:checked]:hidden">
                                Оставить отзыв
                            </span>
                            <span class="hidden group-has-[.review-toggle:checked]:inline">
                                Свернуть
                            </span>
                        </label>
                    @endif

                    @if ($isBooked)
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2">

                            @if ($alreadyReviewed && $isPast)
                                <span class="inline-flex items-center gap-2 text-sm font-semibold text-white/55">
                                    <svg class="h-4 w-4 text-plate-yellow"
                                         viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"/>
                                    </svg>
                                    Вы оценили тренера
                                </span>
                            @elseif (! $canReview)
                                <span class="inline-flex items-center gap-2 text-sm font-semibold text-[#75d99a]">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/>
                                    </svg>
                                    Вы записаны
                                </span>
                            @endif

                            @if ($canCancel)
                                <div class="flex items-center gap-3 text-sm font-semibold">
                                    <input id="cancel-{{ $train->id }}"
                                           type="checkbox" class="peer sr-only">

                                    <label for="cancel-{{ $train->id }}"
                                           class="inline-flex cursor-pointer items-center gap-2 text-white/45 hover:text-error peer-focus-visible:text-error peer-checked:hidden">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                        </svg>
                                        Отменить запись
                                    </label>

                                    <form method="POST"
                                          action="{{ '/trainings/book/' . $train->id . '/rollback' }}"
                                          class="hidden items-center gap-4 peer-checked:flex">
                                        @csrf
                                        <span class="text-white/65">Отменить запись?</span>
                                        <button type="submit" class="text-error underline underline-offset-4">
                                            Да, отменить
                                        </button>
                                        <label for="cancel-{{ $train->id }}"
                                               class="cursor-pointer text-white/65 hover:text-white">
                                            Нет
                                        </label>
                                    </form>
                                </div>
                            @endif
                        </div>

                    @elseif ($canBook)
                        <form method="POST" action="/trainings/book/{{ $train->id }}/">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-full bg-brand-red px-6 py-2.5 text-sm font-semibold hover:bg-brand-dark">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                                Записаться
                            </button>
                        </form>
                    @endif

                    @if ($canEdit)
                        <a href="{{ '/trainings/edit/' . $train->id }}"
                           class="inline-flex items-center gap-2 text-sm font-semibold text-white/65 hover:text-white">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931z"/>
                            </svg>
                            Изменить
                        </a>

                        <div class="flex items-center gap-3 text-sm font-semibold">
                            <input id="delete-{{ $train->id }}"
                                   type="checkbox" class="peer sr-only">

                            <label for="delete-{{ $train->id }}"
                                   class="inline-flex cursor-pointer items-center gap-2 text-white/45 hover:text-error peer-focus-visible:text-error peer-checked:hidden">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M10 11v6m4-6v6M5.5 7l1 13h11l1-13M9 7V4h6v3"/>
                                </svg>
                                Удалить
                            </label>

                            <form method="POST" action="{{ '/trainings/' . $train->id }}"
                                  class="hidden items-center gap-4 peer-checked:flex">
                                @csrf
                                @method('DELETE')
                                <span class="text-white/65">Удалить тренировку?</span>
                                <button type="submit" class="text-error underline underline-offset-4">
                                    Да, удалить
                                </button>
                                <label for="delete-{{ $train->id }}"
                                       class="cursor-pointer text-white/65 hover:text-white">
                                    Отмена
                                </label>
                            </form>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- ФОРМА ОТЗЫВА --}}
    @if ($canReview)
        <input
            id="review-toggle-{{ $train->id }}"
            type="checkbox"
            class="review-toggle peer sr-only"
            @checked($reopen)
        >

        <form
            method="POST"
            action="{{ '/user/' . $train->trainer_id . '/comment' }}"
            class="hidden flex-col gap-3 border-t border-white/[0.07] pt-4 peer-checked:flex"
        >
            @csrf

            <input type="hidden" name="training_id" value="{{ $train->id }}">

            {{-- ОЦЕНКА --}}
            <fieldset class="flex items-center gap-3">
                <legend class="sr-only">Оценка тренера</legend>
                <span class="text-sm text-white/55" aria-hidden="true">Ваша оценка</span>

                <div class="flex flex-row-reverse">
                    @foreach ([5, 4, 3, 2, 1] as $n)
                        <input
                            id="rating-{{ $train->id }}-{{ $n }}"
                            type="radio"
                            name="rating"
                            value="{{ $n }}"
                            required
                            class="peer sr-only"
                            @checked($mine && (int) old('rating') === $n)
                        >

                        <label
                            for="rating-{{ $train->id }}-{{ $n }}"
                            class="cursor-pointer rounded p-0.5 text-white/25 hover:text-plate-yellow peer-checked:text-plate-yellow peer-focus-visible:ring-2 peer-focus-visible:ring-brand-red [:hover~&]:text-plate-yellow"
                        >
                            <svg class="h-7 w-7" viewBox="0 0 24 24"
                                 fill="currentColor" aria-hidden="true">
                                <path d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"/>
                            </svg>
                            <span class="sr-only">{{ $n }} из 5</span>
                        </label>
                    @endforeach
                </div>
            </fieldset>

            @if ($mine)
                @error('rating')
                <p role="alert" class="flex items-start gap-2 text-sm font-medium text-error">
                    <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 20 20"
                         fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-4a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5a.75.75 0 01.75-.75zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ $message }}</span>
                </p>
                @enderror
            @endif

            {{-- ТЕКСТ ОТЗЫВА --}}
            <div class="flex items-end gap-2 rounded-2xl border border-white/15 bg-brand-ink p-2 focus-within:border-brand-red">
                <label for="review-text-{{ $train->id }}" class="sr-only">
                    Текст отзыва
                </label>

                <textarea
                    id="review-text-{{ $train->id }}"
                    name="comment"
                    rows="1"
                    maxlength="255"
                    required
                    placeholder="Напишите отзыв о тренировке"
                    class="max-h-40 min-h-10 flex-1 resize-none bg-transparent px-3 py-2 text-base placeholder:text-white/35 focus:outline-none [field-sizing:content]"
                >{{ $mine ? old('comment') : '' }}</textarea>

                <button
                    type="submit"
                    aria-label="Отправить отзыв"
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand-red hover:bg-brand-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-red"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                    </svg>
                </button>
            </div>

            @if ($mine)
                @error('comment')
                <p role="alert" class="flex items-start gap-2 text-sm font-medium text-error">
                    <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 20 20"
                         fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-4a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5a.75.75 0 01.75-.75zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ $message }}</span>
                </p>
                @enderror
            @endif
        </form>
    @endif
</article>
