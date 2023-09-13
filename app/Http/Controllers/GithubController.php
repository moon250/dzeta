<?php

namespace App\Http\Controllers;

use App\Http\JsonApiResponse;
use App\Services\CacheService;
use App\Services\GithubService;

class GithubController extends Controller
{
    private readonly CacheService $cache;

    public function __construct()
    {
        $this->cache = new CacheService(GithubService::class);
    }

    public function index(): JsonApiResponse
    {
        return new JsonApiResponse();
    }

    public function commits(): JsonApiResponse
    {
        $commits = $this->cache->manage('commits');

        return new JsonApiResponse([
            'count' => (int) $commits,
        ]);
    }

    public function issues(): JsonApiResponse
    {
        $issues = $this->cache->manage('issues');

        return new JsonApiResponse([
            'count' => (int) $issues,
        ]);
    }

    public function pullRequests(): JsonApiResponse
    {
        $pullRequests = $this->cache->manage('pullRequests');

        return new JsonApiResponse([
            'count' => (int) $pullRequests,
        ]);
    }

    public function starred(): JsonApiResponse
    {
        $starred = $this->cache->manage('starred');

        return new JsonApiResponse([
            'count' => (int) $starred,
        ]);
    }
}
