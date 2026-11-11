@extends('layouts.lk')

@section("lk-content")

    <div class="flex flex-col gap-6">
        <h1 class="display text-3xl md:text-4xl">Доступные тренировки</h1>

        <x-trainings-list :trainings="$trainings" :user="$user"  />

    </div>

@endsection
