<?php

use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api-sevima/login', [WebController::class, 'login']);

Route::get('/api-sevima/{api_keyword}/{id?}/{id2?}', [WebController::class, 'index']);