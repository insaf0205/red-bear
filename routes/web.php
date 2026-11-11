<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckUser;
use App\Http\Middleware\CheckNotUser;
use App\Http\Controllers\TrainingController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;

Route::get('/', [UserController::class, "home"]);

Route::middleware(CheckNotUser::class)->group(function () {
    Route::prefix("login")->group(function () {
        Route::get('', function () {
            return view('login');
        });
        Route::post("", [AuthController::class, "login"]);
    });

    Route::prefix("register")->group(function () {
        Route::get('', function () {
            return view('register');
        });
        Route::post("", [AuthController::class, "register"]);
    });
});


Route::middleware(CheckUser::class)->group(function () {

    Route::prefix("topup")->group(function () {
        Route::get("", [PaymentController::class, "show_payment_page"]);
    });

    Route::prefix("profile")->group(function () {
        Route::get("", [UserController::class, "show_me"]);
        Route::patch("", [UserController::class, "update"]);
    });

    Route::post("user/{user}/comment", [ReviewController::class, "store"]);

    Route::prefix("trainings/book")->group(function () {
        Route::get("", [TrainingController::class, "show_trainings"]);
        Route::post("{training}", [TrainingController::class, "book"]);
        Route::post("{training}/rollback", [TrainingController::class, "unbook"]);
    });

    Route::prefix("payments")->group(function () {
       Route::post("", [PaymentController::class, "store"]);
    });

    Route::prefix("subscriptions")->group(function () {
        Route::get("", [SubscriptionController::class, "show_subscription_page"]);
        Route::post("", [SubscriptionController::class, "activate"]);
    });

    Route::middleware(CheckAdmin::class)->group(function () {
        Route::prefix("trainings")->group(function () {
            Route::get("create", [TrainingController::class, "show_create_training"]);

            Route::prefix("edit")->group(function () {
                Route::get("{training}", [TrainingController::class, "show_edit_training"]);
                Route::patch("{training}", [TrainingController::class, "edit"]);
            });

            Route::delete("{training}", [TrainingController::class, "destroy"]);

            Route::post("create", [TrainingController::class, "store"]);
        });
    });

    Route::get("logout", [AuthController::class, "logout"]);
});



Route::get('/health', function () {
    $status = [];

    // Check Database Connection
    try {
        DB::connection()->getPdo();
        // Optionally, run a simple query
        DB::select('SELECT 1');
        $status['database'] = 'OK';
    } catch (\Exception $e) {
        $status['database'] = 'Error';
    }

    // Check Redis Connection
    try {
        Cache::store('redis')->put('health_check', 'OK', 10);
        $value = Cache::store('redis')->get('health_check');
        if ($value === 'OK') {
            $status['redis'] = 'OK';
        } else {
            $status['redis'] = 'Error';
        }
    } catch (\Exception $e) {
        $status['redis'] = 'Error';
    }

    // Check Storage Access
    try {
        $testFile = 'health_check.txt';
        Storage::put($testFile, 'OK');
        $content = Storage::get($testFile);
        Storage::delete($testFile);

        if ($content === 'OK') {
            $status['storage'] = 'OK';
        } else {
            $status['storage'] = 'Error';
        }
    } catch (\Exception $e) {
        $status['storage'] = 'Error';
    }

    // Determine overall health status
    $isHealthy = collect($status)->every(function ($value) {
        return $value === 'OK';
    });

    $httpStatus = $isHealthy ? 200 : 503;

    return response()->json($status, $httpStatus);
});
