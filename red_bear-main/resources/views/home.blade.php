@extends('layouts.app')

@section('title', 'Red Bear — зал для пауэрлифтинга в Казани')

@section('content')

    @include("components/header")

    {{-- HERO --}}
    <div class="overflow-hidden">
        <div class="mx-auto flex min-h-[100vh] max-w-6xl flex-col items-center justify-center px-5 py-14 md:gap-0 gap-8 md:flex-row md:py-20">

            <div class="md:w-7/12">
                <h1 class="display text-4xl leading-[1.02] sm:text-6xl lg:text-7xl">
                    Тяни тяжело. Бросай на&nbsp;пол.
                </h1>
                <p class="mt-8 max-w-md text-lg leading-relaxed text-white/70">
                    Red Bear — зал для пауэрлифтеров в Казани. Большие веса, магнезия и громкая музыка в подходе здесь не повод для замечаний.
                </p>
                <div class="mt-10 flex flex-wrap items-center gap-x-8 gap-y-4">
                    <a href="/login" class="rounded-full bg-brand-red px-8 py-4 font-semibold hover:bg-brand-dark">
                        Записаться на пробную
                    </a>
                </div>
            </div>

            {{-- блин 25 кг --}}
            <div class="w-full max-w-sm md:w-5/12 md:max-w-none md:shrink-0">
                <svg viewBox="0 0 600 600" role="img" aria-label="Красный блин для штанги"
                     class="block w-full md:w-[125%] md:max-w-none md:translate-x-[18%]">
                    <defs>
                        <path id="rim" d="M300,300 m-228,0 a228,228 0 1,1 456,0 a228,228 0 1,1 -456,0"/>
                    </defs>
                    <circle cx="300" cy="300" r="296" fill="#d6202b"/>
                    <circle cx="300" cy="300" r="270" fill="none" stroke="#8f121a" stroke-width="6"/>
                    <circle cx="300" cy="300" r="190" fill="none" stroke="#8f121a" stroke-width="4"/>
                    <circle cx="300" cy="300" r="120" fill="#b01922"/>
                    <circle cx="300" cy="300" r="62" fill="#1c1d1f"/>
                    <circle cx="300" cy="300" r="26" fill="#8d9096"/>
                    <text font-family="Unbounded, sans-serif" font-weight="800" font-size="30" fill="#f1f1ed">
                        <textPath href="#rim" textLength="1432" lengthAdjust="spacing">RED BEAR&#160;&#160;&#160;&#160;POWERLIFTING&#160;&#160;&#160;&#160;25 KG&#160;&#160;&#160;&#160;</textPath>
                    </text>
                </svg>
            </div>

        </div>
    </div>

    {{-- ТРИ ДВИЖЕНИЯ: фото лежат в public/images/ --}}
    <section id="lifts">
        <div class="mx-auto max-w-6xl px-5 pb-20 md:pb-32">
            <div class="mb-12 flex flex-col gap-4 md:mb-16 md:flex-row md:items-end md:justify-between md:gap-12">
                <h2 class="display text-3xl leading-tight md:text-5xl">Три движения</h2>
                <p class="max-w-sm text-lg leading-relaxed text-white/65">
                    Присед, жим лёжа и становая тяга — это и есть пауэрлифтинг. Весь зал собран под них.
                </p>
            </div>

            <div class="flex flex-col gap-6 md:flex-row md:items-start">
                @php
                    $lifts = [
                        ['squat.jpg',    'Присед',        'Спортсмен в приседе со штангой на плечах',               'object-[25%_50%]', ''],
                        ['bench.jpg',    'Жим лёжа',      'Спортсмен выполняет жим лёжа, страхующий стоит сверху',  'object-[50%_60%]', 'md:mt-16'],
                        ['deadlift.jpg', 'Становая тяга', 'Спортсмен в становой тяге на помосте',               'object-[50%_50%]', ''],
                    ];
                @endphp
                @foreach ($lifts as [$file, $name, $alt, $pos, $offset])
                    <figure class="relative w-full overflow-hidden rounded-lg md:flex-1 {{ $offset }}">
                        <img src="{{ asset('images/' . $file) }}" alt="{{ $alt }}"
                             width="1170" height="1972" loading="lazy"
                             class="aspect-[4/5] w-full object-cover md:aspect-[3/4] {{ $pos }}">
                        <figcaption class="display absolute inset-x-0 bottom-0 bg-gradient-to-t from-brand-ink/90 to-transparent px-5 pb-5 pt-16 text-xl">
                            {{ $name }}
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>

    {{-- WHAT YOU CAN'T DO ELSEWHERE --}}
    <section id="about" class="bg-brand-chalk text-brand-ink">
        <div class="mx-auto max-w-6xl px-5 py-20 md:py-28">
            <h2 class="display max-w-3xl text-3xl leading-tight md:text-5xl">
                В обычном фитнес-клубе штангисту скажут «нельзя»
            </h2>
            <p class="mt-6 max-w-xl text-lg leading-relaxed text-brand-ink/70">
                Поэтому найти подходящий зал — задача. В Казани зала именно для пауэрлифтинга нет. Мы его открываем.
            </p>

            <div class="mt-14 flex flex-col">
                <div class="flex border-t border-brand-ink/20">
                    <p class="w-1/2 py-3 text-sm font-semibold text-brand-ink/50">В обычном клубе</p>
                    <p class="w-1/2 py-3 text-sm font-semibold text-brand-red">В Red Bear</p>
                </div>
                @php
                    $rows = [
                        ['Бросить штангу на пол', 'Бросай, помост для этого'],
                        ['Включить музыку на подход', 'Музыка на полную'],
                        ['Использовать магнезию', 'Магнезия и тальк разрешены'],
                        ['Шуметь и кричать', 'Шум здесь нормальный'],
                    ];
                @endphp
                @foreach ($rows as [$no, $yes])
                    <div class="flex border-t border-brand-ink/20">
                        <p class="w-1/2 py-5 pr-4 text-base text-brand-ink/50 line-through decoration-brand-ink/40 md:text-xl">{{ $no }}</p>
                        <p class="w-1/2 py-5 text-base font-semibold md:text-xl">{{ $yes }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FEATURES --}}
    <section id="features">
        <div class="mx-auto flex max-w-6xl flex-col gap-12 px-5 py-20 md:flex-row md:py-28">
            <div class="flex flex-col gap-8 md:sticky md:top-28 md:w-5/12 md:shrink-0 md:self-start">
                <h2 class="display text-3xl leading-tight md:text-5xl">
                    Зал, собранный под силовой спорт
                </h2>
                <img src="{{ asset('images/platform.jpg') }}"
                     alt="Штанга с жёлтым блином на помосте в тёмном зале"
                     width="960" height="1280" loading="lazy"
                     class="aspect-[4/3] w-full rounded-lg object-cover md:max-w-sm">
            </div>

            <dl class="md:w-7/12">
                @php
                    $features = [
                        ['Оборудование для пауэрлифтинга', 'Штанги, блины и помост рассчитаны на присед, жим и тягу. В залах для тяжёлой атлетики оборудование другое.'],
                        ['Своя команда', 'У зала есть собственная команда по пауэрлифтингу. Можно тренироваться рядом с теми, кто выходит на помост.'],
                        ['Помощь по технике', 'Здесь принято подсказывать друг другу и страховать. Новичок быстрее ставит технику, опытный проверяет свою.'],
                        ['Дисциплина и уважение', 'Дружная атмосфера, в которой все работают всерьёз и уважают чужие подходы.'],
                    ];
                @endphp
                @foreach ($features as [$title, $text])
                    <div class="border-t border-white/15 py-7 first:border-t-0 first:pt-0">
                        <dt class="text-xl font-semibold">{{ $title }}</dt>
                        <dd class="mt-2 max-w-lg leading-relaxed text-white/65">{{ $text }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    {{-- КАК НАЧАТЬ --}}
    <section id="start" class="bg-brand-card">
        <div class="mx-auto max-w-6xl px-5 py-20 !pb-[60px]">
            <h2 class="display max-w-3xl text-3xl leading-tight md:text-5xl">
                Как попасть на первую тренировку
            </h2>

            <ol class="mt-14 flex flex-col">
                @php
                    $steps = [
                        ['Выбери запись', 'Позвоним за час до тренировки.'],
                        ['Приди в зал', 'Познакомишься с командой, посмотришь помост и оборудование.'],
                        ['Тренируйся как привык', 'С музыкой, магнезией и страховкой от тех, кто рядом.'],
                    ];
                @endphp
                @foreach ($steps as $i => [$title, $text])
                    <li class="flex flex-col gap-3 border-t border-white/15 py-8 md:flex-row md:items-baseline md:gap-12">
                        <span class="display text-4xl text-brand-red md:w-24 md:shrink-0">{{ $i + 1 }}</span>
                        <h3 class="text-xl font-semibold md:w-1/3 md:shrink-0">{{ $title }}</h3>
                        <p class="max-w-md leading-relaxed text-white/65">{{ $text }}</p>
                    </li>
                @endforeach
            </ol>

            @if(isset($user) && $user->role === "user")
                <div class="mt-14 space-y-6">

                    <h2 class="text-xl font-semibold">Доступные тренировки</h2>

                    <x-trainings-list :user="$user" :trainings="$trainings" />

                </div>

            @endif


        </div>


    </section>

    {{-- CONTACTS --}}
    <section id="contacts">
        <div class="mx-auto max-w-6xl px-5 py-24 md:py-32">
            <h2 class="display max-w-4xl text-4xl leading-[1.05] md:text-6xl">
                Приходи на пробную тренировку
            </h2>
            <p class="mt-6 max-w-lg text-lg leading-relaxed text-white/70">
                Познакомишься с командой, посмотришь оборудование и поднимешь штангу без чьих-либо замечаний.
            </p>
            <div class="mt-10 flex flex-wrap gap-4">
                <a href="/profile" class="rounded-full bg-brand-red px-8 py-4 font-semibold hover:bg-brand-dark">
                    Записаться
                </a>
            </div>
        </div>
    </section>

    @include("components/footer")

@endsection
