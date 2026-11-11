@extends('layouts.lk')

@section("lk-content")

    <div class="flex flex-col gap-14">
        <h1 class="display text-3xl md:text-4xl">Профиль</h1>

        <div class="space-y-6">

            <x-balance :user="$user" :balance="$user->balance" href="/wallet/topup" />

            <x-lk.profile :user="$user"/>

            <x-subscription :user="$user" />

        </div>


        <x-lk.bookings :user="$user"/>

        @if($user->role === "user")

            <div class="space-y-6">

                <h2 class="text-xl font-semibold">Доступные тренировки</h2>

                <x-trainings-list :user="$user" :trainings="$trainings" />

            </div>

            <div class="mt-10 space-y 6">
                <h2 class="text-xl font-semibold">Законченные тренировки</h2>

                <x-trainings-list :user="$user" :trainings="$past_trainings" />
            </div>

        @endif


    </div>

@endsection
