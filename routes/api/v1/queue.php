<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueueController;

Route::prefix('queues')->group(function () {

    Route::get('/', [QueueController::class, 'index'])->name('api.v1.queues.list');
    Route::get('{id}', [QueueController::class, 'show'])->name('api.v1.queues.show');

});
