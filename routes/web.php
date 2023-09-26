<?php

use App\Http\Controllers\QueueController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/image', [ApiController::class,'showImage'])->name('web.image.show');
Route::get('/queues/page/{id}', [QueueController::class, 'showPage'])->name('web.queues.show-page');
Route::post('/image/detect-lp-page',[ApiController::class,'detectLpPage'])->name('web.images.detect-page');


