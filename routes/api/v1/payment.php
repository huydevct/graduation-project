<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\PaymentController;

Route::prefix('payment')->middleware('auth.api')->group(function () {

    Route::prefix('paypal')->group(function () {

        Route::post('/', [PaymentController::class, 'createOrderPaypal'])->name('api.v1.payment.store_paypal');

    });


    Route::get('{id}', [PaymentController::class, 'show'])->name('api.v1.payment.show');

});


Route::prefix('payment')->group(function () {

    Route::any('paypal/webhook', [PaymentController::class, 'webhookPaypal'])->name('api.v1.payment.paypal.webhook');

});
