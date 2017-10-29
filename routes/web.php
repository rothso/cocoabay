<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@welcome');
Route::get('/legal/tos', 'LegalController@tos')->name('tos');

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showInstructions')->name('register');

// TODO: remove route, replace with single home index at "/"
Route::get('/home', 'HomeController@index')->name('home');

// DMV Routes...
Route::prefix('dmv')->group(function () {
    Route::get('/', 'DmvController@index')->name('dmv');

    // Drivers Licenses
    Route::get('license', 'LicenseController@create')->name('license');
    Route::post('license', 'LicenseController@store');
    Route::patch('license', 'LicenseController@update');

    // Vehicle registration
    Route::resource('plate', 'LicensePlateController', ['only' => ['store', 'update']]);
});
