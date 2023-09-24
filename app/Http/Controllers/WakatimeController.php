<?php

namespace App\Http\Controllers;

use App\Http\JsonApiResponse;
use App\Services\CacheService;
use App\Services\WakatimeService;

class WakatimeController extends Controller
{
    private CacheService $cache;

    public function __construct()
    {
        $this->cache = new CacheService(WakatimeService::class);
    }

    public function index(): JsonApiResponse
    {
        return $this->buildIndexResponse();
    }

    public function weekly(): JsonApiResponse
    {
        return new JsonApiResponse([
            'stats' => $this->cache->manage('weeklyStats'),
        ]);
    }

    public function yearly(): JsonApiResponse
    {
        return new JsonApiResponse([
            'stats' => $this->cache->manage('yearlyStats'),
        ]);
    }

    public function allTime(): JsonApiResponse
    {
        return new JsonApiResponse([
            'stats' => $this->cache->manage('allTimeStats'),
        ]);
    }
}
