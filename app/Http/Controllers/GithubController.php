<?php

namespace App\Http\Controllers;

use App\Http\JsonApiResponse;
use App\Services\GithubService;
use Illuminate\Support\Facades\Cache;

class GithubController extends Controller
{
    public function __construct(
        private readonly GithubService $service
    ) {
    }

    public function index(): JsonApiResponse
    {
        return new JsonApiResponse();
    }

    public function commits(): JsonApiResponse
    {
        if (Cache::has('commits')) {
            $commits = Cache::get('commits');
        } else {
            $commits = $this->service->commits();
            Cache::set('commits', $commits, 30 * 60);
        }

        return new JsonApiResponse([
            'count' => (int) $commits,
        ]);
    }

    public function issues(): JsonApiResponse
    {
        if (Cache::has('issues')) {
            $issues = Cache::get('issues');
        } else {
            $issues = $this->service->issues();
            Cache::set('issues', $issues, 30 * 60);
        }

        return new JsonApiResponse([
            'count' => (int) $issues,
        ]);
    }

    public function pullRequests(): JsonApiResponse
    {
        if (Cache::has('pullRequests')) {
            $pullRequests = Cache::get('pullRequests');
        } else {
            $pullRequests = $this->service->pullRequests();
            Cache::set('pullRequests', $pullRequests, 30 * 60);
        }

        return new JsonApiResponse([
            'count' => (int) $pullRequests,
        ]);
    }

    public function starred(): JsonApiResponse
    {
        if (Cache::has('starred')) {
            $starred = Cache::get('starred');
        } else {
            $starred = $this->service->starred();
            Cache::set('starred', $starred, 30 * 60);
        }

        return new JsonApiResponse([
            'count' => (int) $starred,
        ]);
    }
}
