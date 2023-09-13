<?php

namespace Http\Controller;

use App\Services\SpotifyService;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class SpotifyControllerTest extends TestCase
{
    public function testRetrievingSpotifyEndpoints()
    {
        $this->instance(
            SpotifyService::class,
            Mockery::mock(
                SpotifyService::class,
                function (MockInterface $mock) {
                    $mock->shouldReceive('recentlyPlayed')->once();
                }
            )
        );

        $this->getJson('/spotify/recently-played')->assertOk();
    }

    public function testRecentlyPlayedAreCached()
    {
        $this->instance(
            SpotifyService::class,
            Mockery::mock(
                SpotifyService::class,
                function (MockInterface $mock) {
                    $mock->shouldReceive('recentlyPlayed')->once();
                }
            )
        );

        $this->getJson('/spotify/recently-played')->assertOk();

        $this->assertTrue(Cache::has('spotify.recentlyPlayed'));
    }
}
