<?php

Route::post('/city', 'Api\CityController@store');

Route::patch('/city/{city}', 'Api\CityController@update');

Route::delete('/city/{city}', 'Api\CityController@destroy');