@props(['user'])


<header class="sticky top-0 z-50 bg-brand-ink/95 backdrop-blur" style="top: env(safe-area-inset-top, 0px)">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-5 py-4">
        <a href="/" class="display text-xl">Red Bear</a>
        <nav class="hidden gap-8 text-sm text-white/70 md:flex">
            <a href="/#about" class="hover:text-white">Зачем нам свой зал</a>
            <a href="/#features" class="hover:text-white">Что внутри</a>
            <a href="/#start" class="hover:text-white">Как начать</a>
        </nav>

        @if(isset($user))
            <x-lk.avatar-menu :user="$user"/>
        @else
            <div class="flex gap-2 items-center">
                <a href="/login" class="rounded-full bg-brand-red px-5 py-2 text-sm font-semibold hover:bg-brand-dark flex items-center gap-1.5">
                    Войти
                </a>
                <a href="/register" class="rounded-full text-sm border border-white/30 px-8 py-2 font-semibold hover:border-white/50 hidden sm:flex">
                    Регистрация
                </a>
            </div>
        @endif

    </div>
</header>
