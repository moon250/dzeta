<?php

use App\Http\JsonApiResponse;
use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index');
Route::get('/me', 'AboutMeController@index');
Route::get('/ping', fn () => new JsonApiResponse(['message' => 'ζ Alive !']));

Route::group(['prefix' => 'oauth', 'middleware' => 'web'], function () {
    Route::get('/spotify/link', 'Oauth\OauthSpotifyController@link');
    Route::get('/spotify/check', 'Oauth\OauthSpotifyController@check');
});

Route::group(['prefix' => 'github'], function () {
    Route::get('/', 'GithubController@index');

    Route::get('/commits', 'GithubController@commits');
    Route::get('/issues', 'GithubController@issues');
    Route::get('/pull-requests', 'GithubController@pullRequests');
    Route::get('/starred', 'GithubController@starred');
});

Route::group(['prefix' => 'spotify'], function () {
    Route::get('/', 'SpotifyController@index');
    Route::get('/recently-played', 'SpotifyController@recentlyPlayed');
});

Route::group(['prefix' => 'wakatime'], function () {
    Route::get('/', 'WakatimeController@index');
    Route::get('/weekly', 'WakatimeController@weekly');
    Route::get('/yearly', 'WakatimeController@yearly');
    Route::get('/all-time', 'WakatimeController@allTime');
});
