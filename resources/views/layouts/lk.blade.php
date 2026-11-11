@extends('layouts.app')

@section('title', 'Личный кабинет — Red Bear')

@section('content')
    <div class="flex min-h-[100vh]">

        @include('components.lk.sidebar')

        <div class="flex min-w-0 flex-1 flex-col">

            {{-- ВЕРХНЯЯ ПАНЕЛЬ --}}
            <div
                class="sticky top-0 z-30 flex items-center justify-between border-b border-white/10 bg-brand-ink px-5 py-3">
                <a href="/" class="display text-xl lg:hidden">Red Bear</a>
                <span class="hidden text-sm text-white/55 lg:block">Личный кабинет</span>

                <x-lk.avatar-menu :user="$user"/>
            </div>

            {{-- СОДЕРЖИМОЕ --}}
            <main class="w-full px-5 pb-32 pt-10 lg:pb-16">
                @yield("lk-content")
            </main>
        </div>
    </div>
@endsection
