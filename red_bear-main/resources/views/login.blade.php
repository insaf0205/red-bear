@extends('layouts.app')

@section('title', 'Вход — Red Bear')

@section('content')

    @include("components/header")

    <main class="mx-auto flex min-h-[100vh] max-w-6xl flex-col lg:flex-row items-center justify-between px-5 gap-8 py-14">

        <div class="w-full lg:w-5/12 lg:shrink-0">
            <h1 class="display text-3xl leading-tight md:text-4xl">Вход</h1>
            <p class="mt-3 text-white/65">Войди, чтобы записаться на тренировку.</p>

            <form class="mt-10 flex flex-col gap-6" action="/login" method="POST">

                @csrf

                {{session("message")}}

                <div class="flex flex-col gap-2">
                    <label for="email" class="text-sm font-semibold">Email</label>
                    <input
                        id="email" name="email" type="email"
                        class="rounded-lg border border-white/15 bg-brand-card px-4 py-3 text-base placeholder:text-white/30"
                        placeholder="name@example.com"
                    >

                    @error('email')
                    <p id="email-error" role="alert" class="flex items-start gap-2 text-sm font-medium text-error">
                        <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-4a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 6zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                    @enderror

                </div>

                <div class="flex flex-col gap-2">
                    <div class="flex items-baseline justify-between">
                        <label for="password" class="text-sm font-semibold">Пароль</label>
{{--                        <a href="/forgot-password" class="text-sm text-white/55 hover:text-white">Забыли пароль?</a>--}}
                    </div>
                    <input
                        id="password" name="password" type="password"
                        class="rounded-lg border border-white/15 bg-brand-card px-4 py-3 text-base placeholder:text-white/30"
                    >
                    @error('password')
                    <p id="password-error" role="alert" class="flex items-start gap-2 text-sm font-medium text-error">
                        <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-4a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 6zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                    @enderror
                </div>

                <button type="submit" class="rounded-full bg-brand-red px-8 py-4 font-semibold hover:bg-brand-dark">
                    Войти
                </button>
            </form>

            <p class="mt-8 text-sm text-white/60">
                Нет аккаунта?
                <a href="/register" class="font-semibold text-white underline underline-offset-4 hover:text-brand-red">Зарегистрироваться</a>
            </p>
        </div>

        <img src="/images/platform.jpg" alt="Штанга с жёлтым блином на помосте"
             width="960" height="1280"
             class="aspect-[3/4] w-full max-w-md rounded-lg object-cover hidden lg:flex">

    </main>

    <div class="mt-10">
        @include("components/footer")
    </div>


@endsection
