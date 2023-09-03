<?php

namespace Http\Controller;

use App\Services\GithubService;
use Illuminate\Support\Facades\Cache;
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

    public function testIssuesAreCached()
    {
        Cache::spy();

        $this->instance(
            GithubService::class,
            Mockery::mock(
                GithubService::class,
                function (MockInterface $mock) {
                    $mock->shouldReceive('issues')->once();
                }
            )
        );

        $this->getJson('/github/issues')->assertOk();

        Cache::shouldHaveReceived('set')
            ->once()
            ->with('issues', 0, 30 * 60);
    }

    public function testPullRequestsAreCached()
    {
        Cache::spy();

        $this->instance(
            GithubService::class,
            Mockery::mock(
                GithubService::class,
                function (MockInterface $mock) {
                    $mock->shouldReceive('pullRequests')->once();
                }
            )
        );

        $this->getJson('/github/pull-requests')->assertOk();

        Cache::shouldHaveReceived('set')
            ->once()
            ->with('pullRequests', 0, 30 * 60);
    }

    public function testStarredAreCached()
    {
        Cache::spy();

        $this->instance(
            GithubService::class,
            Mockery::mock(
                GithubService::class,
                function (MockInterface $mock) {
                    $mock->shouldReceive('starred')->once();
                }
            )
        );

        $this->getJson('/github/starred')->assertOk();

        Cache::shouldHaveReceived('set')
            ->once()
            ->with('starred', 0, 30 * 60);
    }

    public function testCommitsAreCache()
    {
        Cache::spy();

        $this->instance(
            GithubService::class,
            Mockery::mock(
                GithubService::class,
                function (MockInterface $mock) {
                    $mock->shouldReceive('commits')->once();
                }
            )
        );

        $this->getJson('/github/commits')->assertOk();

        Cache::shouldHaveReceived('set')
            ->once()
            ->with('commits', 0, 30 * 60);
    }
}
