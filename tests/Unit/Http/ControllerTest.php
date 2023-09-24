<?php

namespace Http;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ControllerTest extends TestCase
{
    public function testListingUriEndpoints()
    {
        $controller = new Controller();
        $response = $controller->buildIndexContent();

        $this->assertNotContains(url('test'), $response);

        $this->assertSame([
            '1' => url('test/1'),
            'two' => url('test/two'),
            'three-long_3' => url('test/three-long_3'),
        ], $response);
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->instance(
            Router::class,
            Mockery::mock(
                Router::class,
                function (MockInterface $mock) {
                    $mock->shouldReceive('getCurrentRoute')->andReturn(new class
                    {
                        public string $uri = 'test';
                    });

                    $mock->shouldReceive('getRoutes')->andReturn(new class
                    {
                        public function getRoutes()
                        {
                            return [
                                new Route('GET', '/test', function () {
                                }),
                                new Route('GET', '/test/1', function () {
                                }),
                                new Route('GET', '/test/two', function () {
                                }),
                                new Route('GET', '/test/three-long_3', function () {
                                }),
                            ];
                        }
                    });
                }
            )
        );
    }
}
