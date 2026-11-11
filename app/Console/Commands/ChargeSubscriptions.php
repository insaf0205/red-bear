<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChargeSubscriptions extends Command
{
    // Цена одна на весь проект: меняешь здесь, а в activate() и на странице подставляешь отсюда
    public const PRICE = 1400;

    protected $signature = 'subscriptions:charge';
    protected $description = 'Продлить абонементы: списать ежемесячную плату с баланса';

    public function handle(): int
    {
        // subscription_activated_at != null  -> абонемент включён (автопродление идёт)
        // last_charged_at                    -> начало оплаченного периода; оплачено до last_charged_at + 1 месяц
        // eachById, а не each: мы меняем last_charged_at, и обычный each пропускал бы записи.
        User::query()
            ->whereNotNull('subscription_activated_at')
            ->whereNotNull('last_charged_at')
            ->where('last_charged_at', '<=', now()->subMonthNoOverflow())
            ->eachById(function (User $candidate) {
                DB::transaction(fn () => $this->renew($candidate->id));
            });

        $this->info('Готово');

        return self::SUCCESS;
    }

    public function renew(string $userId): void
    {
        // Блокируем строку: параллельное пополнение или покупка не собьют проверку баланса
        $user = User::lockForUpdate()->find($userId);

        if (! $user || ! $user->subscription_activated_at || ! $user->last_charged_at) {
            return;
        }

        $period = Carbon::parse($user->last_charged_at);

        // Уже продлён другим запуском
        if ($period->copy()->addMonthNoOverflow()->isFuture()) {
            return;
        }

        // Не хватает денег: абонемент заканчивается и больше не продлевается,
        // пока человек не купит его снова (иначе после пополнения его «догнали» бы списаниями)
        if ($user->balance < self::PRICE) {
            $user->update(['subscription_activated_at' => null]);
            Log::info("Абонемент пользователя {$user->id} закончился: на балансе {$user->balance}");

            return;
        }

        // Списываем ОДИН раз. Если планировщик долго не работал, период подтягиваем
        // к последней границе месяца, а не списываем задним числом за каждый пропущенный месяц
        $next = $period->copy()->addMonthNoOverflow();
        while ($next->copy()->addMonthNoOverflow()->lessThanOrEqualTo(now())) {
            $next->addMonthNoOverflow();
        }

        $user->decrement('balance', self::PRICE);
        $user->update(['last_charged_at' => $next]);

        Log::info('Списали ' . self::PRICE . " с пользователя {$user->id}, баланс: {$user->balance}");
    }
}
