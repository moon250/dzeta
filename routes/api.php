<?php

use App\Http\JsonApiResponse;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => new JsonApiResponse(['message' => 'ζ Alive !']));

Route::group(['prefix' => 'oauth', 'middleware' => 'web'], function () {
    Route::get('/spotify/link', 'Oauth\OauthSpotifyController@link');
    Route::get('/spotify/check', 'Oauth\OauthSpotifyController@check');
});

Route::get('/me', 'AboutMeController@index');

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
