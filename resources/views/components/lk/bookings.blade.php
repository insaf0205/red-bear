@props(['user'])

<section id="bookings" class="flex flex-col gap-6">

    @if($user->role === 'user')
        <div class="flex items-center justify-between gap-4">
            <h2 class="text-xl font-semibold">Мои записи</h2>
            <a href="/trainings/book" class="rounded-full bg-brand-red px-5 py-2 text-sm font-semibold hover:bg-brand-dark">
                Записаться
            </a>
        </div>

        <div class="flex flex-col">
            @forelse($user->user_trainings as $train)
                @if($train->end_at > \Carbon\Carbon::now("UTC"))
                    <x-train :train="$train" :user="$user" />
                @endif
            @empty
                <p class="text-white/40">Планируемых тренировок не выбрано</p>
            @endforelse
        </div>
    @endif

    @if($user->role === 'admin')
        <div class="flex items-center justify-between gap-4">
            <h2 class="text-xl font-semibold">Предстоящие тренировки</h2>
            <a href="/trainings/create" class="rounded-full bg-brand-red px-5 py-2 text-sm font-semibold hover:bg-brand-dark">
                Создать
            </a>
        </div>

        <div class="flex flex-col">
            @forelse($user->trainer_trainings as $train)
                @if($train->end_at > \Carbon\Carbon::now("UTC"))
                    <x-train :train="$train" :user="$user" />
                @endif
            @empty
                <p class="text-white/40">У вас нет предстоящих тренировок</p>
            @endforelse
        </div>
    @endif

</section>
