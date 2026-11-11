<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('subscriptions:charge')
    ->everyMinute()          // раз в день проверяем, кому пора
    ->withoutOverlapping()       // не запускать второй экземпляр, если первый ещё бежит
    ->onOneServer();             // если несколько инстансов приложения — только на одном
