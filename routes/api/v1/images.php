<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::prefix('images')->group(function (){

    Route::post('detect-lp',[ApiController::class,'detectLp'])->name('api.v1.images.detect');
    Route::post('detect-object',[ApiController::class,'detectObject'])->name('api.v1.images.detect-object');

});
