<?php

namespace App\Http\Controllers\Oauth;

use App\Enums\OauthService;
use App\Http\Controllers\Controller;
use App\Models\OauthCredential;
use App\Services\SpotifyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Spotify\Provider;

class OauthSpotifyController extends Controller
{
    public function link(): RedirectResponse
    {
        /** @var Provider $driver */
        $driver = Socialite::driver('spotify');

        return $driver->scopes(SpotifyService::ACCESS_TOKEN_SCOPES)
            ->redirect();
    }

    public function check(): RedirectResponse
    {
        /** @var Provider $driver */
        $driver = Socialite::driver('spotify');

        $user = $driver->user();

        OauthCredential::updateOrCreate(['service' => OauthService::Spotify->value], [
            'user_id' => $user->getId(),
            'access_token' => $user->accessTokenResponseBody['access_token'],
            'refresh_token' => $user->accessTokenResponseBody['refresh_token'],
            'expires_at' => Carbon::now()->addSeconds(3600),
        ]);

        return redirect('/');
    }
}
