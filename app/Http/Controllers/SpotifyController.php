<?php

namespace App\Http\Controllers;

use App\Http\JsonApiResponse;
use App\Services\SpotifyService;
use Illuminate\Support\Facades\Cache;

class SpotifyController extends Controller
{
    public function __construct(
        private readonly SpotifyService $service
    ) {
    }

    public function recentlyPlayed(): JsonApiResponse
    {
        if (Cache::has('spotify.recentlyPlayed')) {
            $items = Cache::get('spotify.recentlyPlayed');
        } else {
            $items = $this->service->recentlyPlayed();
            Cache::set('issues', $items, 30 * 60);
        }

        return new JsonApiResponse([
            'items' => $items,
        ]);
    }
}
