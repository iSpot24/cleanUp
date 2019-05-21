<?php

use Illuminate\Http\Request;

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

Route::post('/login', 'Api\UserController@login');

Route::middleware('auth:api')->group(function () {

    Route::get('/bookings', 'Api\BookingController@index');

    @include ('api/cities.php');
    @include ('api/cleaners.php');
});

Route::middleware('guest:api')->group(function () {

    Route::get('/cleaners/search', 'Api\CleanerController@search');

    Route::post('/booking', 'Api\BookingController@store');
});
