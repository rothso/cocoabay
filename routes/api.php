<?php

use App\Http\Middleware\LslProtected;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Registration & password update hybrid
Route::post('register', 'Auth\RegisterController@register')->middleware(LslProtected::class);
