<?php

Route::post('/cleaner', 'Api\CleanerController@store');

Route::patch('/cleaner/{cleaner}', 'Api\CleanerController@update');

Route::delete('/cleaner/{cleaner}', 'Api\CleanerController@destroy');