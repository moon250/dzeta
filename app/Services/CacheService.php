<?php

namespace App\Services;

use App\Exceptions\Cache\InvalidMethodNameException;
use App\Exceptions\Cache\InvalidServiceNameException;
use Exception;
use Illuminate\Support\Facades\Cache;
use ReflectionClass;

readonly class CacheService
{
    /**
     * @param  class-string  $service Service's class-string
     */
    public function __construct(
        private string $service
    ) {
    }

    /**
     * Automatically manage cache for service methods.
     *
     * @param  string  $method Service's method to retrieve value (through cache, or directly and caching value)
     * @param  int  $ttl Cache ttl value
     *
     * @throws Exception Thrown if $method does not exist on given service
     */
    public function manage(string $method, int $ttl = 3600): mixed
    {
        $name = $this->getServiceName();
        $key = "$name.$method";

        if (Cache::has($key)) {
            return Cache::get($key);
        }

        if (!$this->hasMethod($method)) {
            throw new InvalidMethodNameException("Method $method was not found on class $this->service");
        }

        $data = $this->callServiceMethod($method);

        Cache::set($key, $data, $ttl);

        return $data;
    }

    private function callServiceMethod(string $method): mixed
    {
        $service = app($this->service);

        return $service->{$method}();
    }

    private function hasMethod(string $method): bool
    {
        $reflected = new ReflectionClass($this->service);

        return $reflected->hasMethod($method);
    }

    private function getServiceName(): string
    {
        $service = last(explode('\\', $this->service));

        // Regex that split camelCase (and PascalCase) into an array of words. We just need to get the first word, in lowercase
        // https://stackoverflow.com/a/4519756
        $name = preg_split('/(?=[A-Z])/', $service, flags: PREG_SPLIT_NO_EMPTY);

        // Should not happen unless given service name is empty
        if ($name === false || count($name) === 0) {
            throw new InvalidServiceNameException("Could not find any valuable name in service's name ($service). Is it camelCase or PascalCase ?");
        }

        return mb_strtolower($name[0]);
    }
}
