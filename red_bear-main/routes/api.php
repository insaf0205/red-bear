<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::post('/payment-webhook', [PaymentController::class, 'updateStatus']);
