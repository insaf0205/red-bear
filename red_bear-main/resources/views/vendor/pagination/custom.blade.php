@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Пагинация"
         class="flex flex-wrap items-center justify-center gap-2">

        {{-- Назад --}}
        @if ($paginator->onFirstPage())
            <span aria-disabled="true"
                  class="flex h-10 w-10 cursor-not-allowed items-center justify-center rounded-xl border border-white/[0.06] text-white/20">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="m15 18-6-6 6-6"/>
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               rel="prev" aria-label="Предыдущая страница"
               class="flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 text-white/65 transition hover:border-brand-red hover:bg-white/[0.04] hover:text-white">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="m15 18-6-6 6-6"/>
                </svg>
            </a>
        @endif

        {{-- Страницы --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span aria-disabled="true"
                      class="flex h-10 min-w-10 items-center justify-center px-2 text-sm text-white/35">
                    {{ $element }}
                </span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page"
                              class="flex h-10 min-w-10 items-center justify-center rounded-xl border border-brand-red bg-brand-red px-3 text-sm font-semibold text-white shadow-lg shadow-brand-red/10">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="flex h-10 min-w-10 items-center justify-center rounded-xl border border-white/10 px-3 text-sm font-medium text-white/60 transition hover:border-white/25 hover:bg-white/[0.05] hover:text-white">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Вперёд --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               rel="next" aria-label="Следующая страница"
               class="flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 text-white/65 transition hover:border-brand-red hover:bg-white/[0.04] hover:text-white">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="m9 18 6-6-6-6"/>
                </svg>
            </a>
        @else
            <span aria-disabled="true"
                  class="flex h-10 w-10 cursor-not-allowed items-center justify-center rounded-xl border border-white/[0.06] text-white/20">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="m9 18 6-6-6-6"/>
                </svg>
            </span>
        @endif

    </nav>
@endif
