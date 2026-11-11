<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ChargeMonthlySubscription implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        User::query()
            ->where('balance', '>=', 100) // например, стоимость подписки
            ->where('subscription_active', true)
            ->each(function (User $user) {
                $user->decrement('balance', 100);
                Log::info("Списали 100 с пользователя {$user->id}");
            });
    }
}
