<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    require "images.php";
    require "queue.php";
    require "videos.php";
    require "payment.php";
});
