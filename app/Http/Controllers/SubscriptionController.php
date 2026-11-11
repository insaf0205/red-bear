<?php

namespace App\Http\Controllers;

use App\Console\Commands\ChargeSubscriptions;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{

    public function __construct(
        private ChargeSubscriptions $chargeSubscriptions
    )
    {

    }

    public function activate()
    {
        $result = DB::transaction(function () {
            // блокируем строку: двойной клик или параллельный запрос не спишут деньги дважды
            $user = User::lockForUpdate()->findOrFail(auth()->id());

            if ($user->hasSubscription()) {
                return back()->with('toast', [
                    'type' => 'error',
                    'message' => 'У вас уже сейчас действует подписка',
                ]);
            }

            if ($user->subscription_activated_at) {
                $this->chargeSubscriptions->renew($user->id);
            }

            if ($user->balance < ChargeSubscriptions::PRICE) {
                return back()->with('toast', [
                    'type' => 'error',
                    'message' => 'Недостаточно средств',
                ]);
            }

            $user->decrement('balance', ChargeSubscriptions::PRICE);
            $user->update([
                'subscription_activated_at' => now(),   // автопродление включено
                'last_charged_at' => now(),             // начало оплаченного месяца
            ]);

            $user->increment("free_sessions_with_coach");

            return redirect("/profile")->with("toast", [
                'type' => 'success',
                'message' => 'Подписка успешно оформлена',
            ]);
        });

        return match ($result) {
            'already'  => back()->withErrors(['subscription' => 'Абонемент уже активен.']),
            'no_money' => back()->withErrors(['subscription' => 'Недостаточно средств. Пополните баланс.']),
            default    => redirect('/profile'),
        };
    }

    public function show_subscription_page()
    {
        $user = auth()->user();

        return view("subscriptions", compact("user"));
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscription $subscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subscription $subscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {
        //
    }
}
