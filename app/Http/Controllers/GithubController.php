<?php

namespace App\Http\Controllers;

use App\Http\JsonApiResponse;
use App\Services\GithubService;

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
        return new JsonApiResponse([
            'count' => $this->service->commits(),
        ]);
    }

    public function issues(): JsonApiResponse
    {
        return new JsonApiResponse([
            'count' => $this->service->issues(),
        ]);
    }

    public function pullRequests(): JsonApiResponse
    {
        return new JsonApiResponse([
            'count' => $this->service->pullRequests(),
        ]);
    }

    public function starred(): JsonApiResponse
    {
        return new JsonApiResponse([
            'count' => $this->service->starred(),
        ]);
    }
}
