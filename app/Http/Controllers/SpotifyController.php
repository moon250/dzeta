<?php

namespace App\Http\Controllers;

use App\Http\JsonApiResponse;
use App\Services\CacheService;
use App\Services\SpotifyService;

class SpotifyController extends Controller
{
    private readonly CacheService $cache;

    public function __construct()
    {
        $this->cache = new CacheService(SpotifyService::class);
    }

    public function index(): JsonApiResponse
    {
        return new JsonApiResponse($this->buildIndexResponse());
    }

    public function recentlyPlayed(): JsonApiResponse
    {
        $items = $this->cache->manage('recentlyPlayed');

        return new JsonApiResponse([
            'items' => $items,
        ]);
    }
}
