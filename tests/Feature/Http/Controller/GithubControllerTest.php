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

        $this->assertTrue(Cache::has('github.issues'));
    }

    public function testPullRequestsAreCached()
    {
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

        $this->assertTrue(Cache::has('github.pullRequests'));
    }

    public function testStarredAreCached()
    {
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

        $this->assertTrue(Cache::has('github.starred'));
    }

    public function testCommitsAreCached()
    {
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

        $this->assertTrue(Cache::has('github.commits'));
    }
}
