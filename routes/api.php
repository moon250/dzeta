<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => ['message' => 'ζ Alive !']);

Route::get('/me', 'AboutMeController@index');
