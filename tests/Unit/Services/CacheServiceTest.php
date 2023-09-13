<?php

namespace Services;

use App\Exceptions\Cache\InvalidMethodNameException;
use App\Exceptions\Cache\InvalidServiceNameException;
use App\Services\CacheService;
use App\Services\GithubService;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class CacheServiceTest extends TestCase
{
    public function testGivingAnNonValuableNameContainingService()
    {
        $this->expectException(InvalidServiceNameException::class);
        $cache = new CacheService('');
        $cache->manage('test');
    }

    public function testManagingCacheForAnUnexistingMethod()
    {
        $this->expectException(InvalidMethodNameException::class);
        $cache = new CacheService(GithubService::class);
        $cache->manage('unexistingMethodObviously');
    }

    public function testManagingCache()
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

        $cache = new CacheService(GithubService::class);
        $cache->manage('pullRequests');

        $this->assertTrue(Cache::has('github.pullRequests'));
    }
}
