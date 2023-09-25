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
                        public function getRoutes(): array
                        {
                            return [
                                new Route('GET', '/test', []),
                                new Route('GET', '/test/1', []),
                                new Route('GET', '/test/two', []),
                                new Route('GET', '/test/three-long_3', []),
                            ];
                        }
                    });
                }
            )
        );

        $controller = new Controller();
        $response = $controller->buildIndexContent();

        $this->assertNotContains(url('test'), $response);

        $this->assertSame([
            '1' => url('test/1'),
            'two' => url('test/two'),
            'three-long_3' => url('test/three-long_3'),
        ], $response);
    }

    public function testListingEndpointsForBaseUri()
    {
        $this->instance(
            Router::class,
            Mockery::mock(
                Router::class,
                function (MockInterface $mock) {
                    $mock->shouldReceive('getCurrentRoute')->andReturn(new class
                    {
                        public string $uri = '/';
                    });

                    $mock->shouldReceive('getRoutes')->andReturn(new class
                    {
                        public function getRoutes(): array
                        {
                            return [
                                new Route('GET', '/test', []),
                                new Route('GET', '/test-two', []),
                                new Route('GET', '/test/deep', []),
                            ];
                        }
                    });
                }
            )
        );

        $controller = new Controller();
        $response = $controller->buildIndexContent();

        $this->assertNotContains(url('test/deep'), $response);

        $this->assertSame([
            'test' => url('test'),
            'test-two' => url('test-two'),
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
                        public function getRoutes(): array
                        {
                            return [
                                new Route('GET', '/test-two', []),
                                new Route('GET', '/test', []),
                                new Route('GET', '/test/1', []),
                                new Route('GET', '/test/two', []),
                                new Route('GET', '/test/three-long_3', []),
                            ];
                        }
                    });
                }
            )
        );
    }
}
