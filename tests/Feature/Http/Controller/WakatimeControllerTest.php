<?php

namespace Http\Controller;

use App\Services\WakatimeService;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class WakatimeControllerTest extends TestCase
{
    public function testRetrievingWakatimeEndpoints()
    {
        $this->getJson('/wakatime/weekly')->assertOk();
        $this->assertTrue(Cache::has('wakatime.weeklyStats'));
        $this->getJson('/wakatime/yearly')->assertOk();
        $this->assertTrue(Cache::has('wakatime.yearlyStats'));
        $this->getJson('/wakatime/all-time')->assertOk();
        $this->assertTrue(Cache::has('wakatime.allTimeStats'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->instance(
            WakatimeService::class,
            Mockery::mock(
                WakatimeService::class,
                function (MockInterface $mock) {
                    $mock->shouldReceive('weeklyStats')->once()->andReturn([]);
                    $mock->shouldReceive('yearlyStats')->once()->andReturn([]);
                    $mock->shouldReceive('allTimeStats')->once()->andReturn([]);
                }
            )
        );
    }
}
