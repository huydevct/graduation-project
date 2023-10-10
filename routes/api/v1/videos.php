<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::prefix('videos')->group(function (){

    Route::post('detect-lp',[ApiController::class,'detectLpVideo'])->name('api.v1.videos.detect');

});
