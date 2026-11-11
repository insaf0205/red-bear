@extends('layouts.lk')

@section("lk-content")

    <div class="flex flex-col gap-14">
        <h1 class="display text-3xl md:text-4xl">Пополнить баланс</h1>

        <x-balance :user="$user" :balance="$user->balance" />

        <x-subscription :user="$user" />

    </div>

@endsection
