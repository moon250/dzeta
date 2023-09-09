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
        if (Cache::has('github.commits')) {
            $commits = Cache::get('github.commits');
        } else {
            $commits = $this->service->commits();
            Cache::set('github.commits', $commits, 30 * 60);
        }

        return new JsonApiResponse([
            'count' => (int) $commits,
        ]);
    }

    public function issues(): JsonApiResponse
    {
        if (Cache::has('github.issues')) {
            $issues = Cache::get('github.issues');
        } else {
            $issues = $this->service->issues();
            Cache::set('github.issues', $issues, 30 * 60);
        }

        return new JsonApiResponse([
            'count' => (int) $issues,
        ]);
    }

    public function pullRequests(): JsonApiResponse
    {
        if (Cache::has('github.pullRequests')) {
            $pullRequests = Cache::get('github.pullRequests');
        } else {
            $pullRequests = $this->service->pullRequests();
            Cache::set('github.pullRequests', $pullRequests, 30 * 60);
        }

        return new JsonApiResponse([
            'count' => (int) $pullRequests,
        ]);
    }

    public function starred(): JsonApiResponse
    {
        if (Cache::has('github.starred')) {
            $starred = Cache::get('github.starred');
        } else {
            $starred = $this->service->starred();
            Cache::set('github.starred', $starred, 30 * 60);
        }

        return new JsonApiResponse([
            'count' => (int) $starred,
        ]);
    }
}
