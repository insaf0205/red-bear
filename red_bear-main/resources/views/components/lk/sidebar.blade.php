@php
    $navItems = [
        [
            'label' => 'Профиль',
            'url' => '/profile',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.118a7.5 7.5 0 0115 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.5-1.632z"/>',
            'roles' => ['user', 'admin'],
        ],
//        [
//            'label' => 'Записи',
//            'url' => '/bookings',
//            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>',
//            'roles' => ['user', 'admin'],
//        ],
        [
            'label' => 'Записаться',
            'url' => '/trainings/book',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>',
            'roles' => ['user'],
        ],
        [
            'label' => 'Купить абонемент',
            'url' => '/subscriptions',
            'icon' => '<rect x="2.5" y="5.5" width="19" height="13" rx="2"/><path stroke-linecap="round" d="M2.5 9.5h19M6 14.5h3"/>',
            'roles' => ['user'],
        ],
        [
            'label' => 'Пополнить счет',
            'url' => '/topup',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9" />',
            'roles' => ['user'],
        ],
//        [
//            'label' => 'Отзывы',
//            'url' => '/reviews',
//            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>',
//            'roles' => ['user', 'admin'],
//        ],
        [
            'label' => 'Выйти',
            'url' => '/logout',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A1.75 1.75 0 0014 3.5H5.75A1.75 1.75 0 004 5.25v13.5A1.75 1.75 0 005.75 20.5H14a1.75 1.75 0 001.75-1.75V15"/><path stroke-linecap="round" stroke-linejoin="round" d="M9.5 12h10.5m0 0l-3.25-3.25M20 12l-3.25 3.25"/>',
            'roles' => ['user', 'admin'],
        ],
    ];

    $navItems = array_values(array_filter(
        $navItems,
        fn ($item) => in_array($user->role, $item['roles'], true)
    ));

    $currentPath = '/' . trim(request()->path(), '/');
@endphp

{{-- ДЕСКТОП --}}
<aside class="hidden w-64 shrink-0 flex-col gap-8 border-r border-white/10 px-4 py-6 lg:sticky lg:top-0 lg:flex lg:h-screen">
    <a href="/" class="display px-4 text-xl">Red Bear</a>

    <nav class="flex flex-col gap-1">
        @foreach ($navItems as $item)
            @php
                $active = $currentPath === $item['url'];
            @endphp

            <a href="{{ $item['url'] }}"
               @if ($active) aria-current="page" @endif
                @class([
                    'flex items-center gap-4 rounded-lg px-4 py-3 transition',
                    'bg-white/10 font-semibold text-white' => $active,
                    'font-medium text-white/70 hover:bg-white/5 hover:text-white' => ! $active,
                ])>
                <svg class="h-6 w-6 shrink-0 {{ $active ? 'text-brand-red' : '' }}"
                     fill="none" viewBox="0 0 24 24"
                     stroke-width="1.75" stroke="currentColor"
                     aria-hidden="true">
                    {!! $item['icon'] !!}
                </svg>

                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>
</aside>

{{-- МОБИЛЬНАЯ ПАНЕЛЬ --}}
<nav class="fixed inset-x-0 bottom-0 z-40 flex border-t border-white/10 bg-brand-ink lg:hidden"
     style="padding-bottom: env(safe-area-inset-bottom, 0px)">
    @foreach ($navItems as $item)
        @php
            $active = $currentPath === $item['url'];
        @endphp

        <a href="{{ $item['url'] }}"
           @if ($active) aria-current="page" @endif
            @class([
                'flex min-w-0 flex-1 flex-col items-center justify-center gap-1 py-2.5 text-[10px] transition sm:text-[11px]',
                'font-semibold text-white' => $active,
                'font-medium text-white/55 hover:text-white' => ! $active,
            ])>
            <svg class="h-6 w-6 shrink-0 {{ $active ? 'text-brand-red' : '' }}"
                 fill="none" viewBox="0 0 24 24"
                 stroke-width="1.75" stroke="currentColor"
                 aria-hidden="true">
                {!! $item['icon'] !!}
            </svg>

            <span class="max-w-full truncate px-0.5">
                {{ $item['label'] }}
            </span>
        </a>
    @endforeach
</nav>
