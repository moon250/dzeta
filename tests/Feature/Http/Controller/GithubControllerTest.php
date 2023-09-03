<?php

namespace Http\Controller;

use App\Services\GithubService;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class GithubControllerTest extends TestCase
{
    public function testRetrievingGithubEndpoints()
    {
        $this->instance(
            GithubService::class,
            Mockery::mock(
                GithubService::class,
                function (MockInterface $mock) {
                    $mock->shouldReceive('issues', 'pullRequests', 'starred', 'commits')->once();
                }
            )
        );

        $this->getJson('/github/issues')->assertOk();
        $this->getJson('/github/pull-requests')->assertOk();
        $this->getJson('/github/starred')->assertOk();
        $this->getJson('/github/commits')->assertOk();
    }
}
