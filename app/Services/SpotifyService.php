<?php

namespace App\Services;

use App\Exceptions\AccessTokenException;
use App\Models\OauthCredential;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

final class SpotifyService
{
    public const ACCESS_TOKEN_ENDPOINT = 'https://accounts.spotify.com/api/token';

    public const API_ENDPOINT = 'https://api.spotify.com/v1';

    public const ACCESS_TOKEN_SCOPES = ['user-read-recently-played', 'user-top-read', 'user-read-private'];

    public function recentlyPlayed(): array
    {
        $response = Http::asForm()
            ->withHeader('Authorization', 'Bearer ' . $this->getAccessToken())
            ->get(self::API_ENDPOINT . '/me/player/recently-played')
            ->json();

        return array_map(fn ($track) => $track['track']['name'], $response['items']);
    }

    private function getAccessToken(): string
    {
        $credentials = OauthCredential::where('service', 'spotify')->get();

        if ($credentials->count() === 0) {
            throw new AccessTokenException('Service spotify was not linked, access token is missing.');
        }

        /** @var OauthCredential $credentials */
        $credentials = $credentials[0];

        if ($this->isExpired($credentials['expires_at'])) {
            return $this->refreshToken($credentials['refresh_token']);
        }

        return $credentials->access_token;
    }

    private function isExpired(string $expires): bool
    {
        $diff = Carbon::now()->diff($expires);

        return $diff->i === 0 || $diff->invert === 1;
    }

    private function refreshToken(string $refreshToken): string
    {
        $data = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'scopes' => self::ACCESS_TOKEN_SCOPES,
        ];

        $response = Http::asForm()
            ->withToken(
                base64_encode(
                    config('services.spotify.client_id') . ':' . config('services.spotify.client_secret')
                ),
                'Basic'
            )
            ->post(self::ACCESS_TOKEN_ENDPOINT, $data)
            ->json();

        $stored = OauthCredential::where('service', 'spotify')->get();

        if ($stored->count() === 0) {
            throw new AccessTokenException('Service spotify was not linked, access token is missing.');
        }

        /** @var OauthCredential $stored */
        $stored = $stored[0];
        $stored->access_token = $response['access_token'];
        $stored->expires_at = Carbon::now()->addSeconds(3600);
        $stored->update();

        return $response['access_token'];
    }
}
