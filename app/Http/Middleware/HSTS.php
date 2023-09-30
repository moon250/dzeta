<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HSTS
{
    /**
     * @param Closure(Request): (JsonResponse) $next
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $response = $next($request);

        $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubdomains');

        return $response;
    }
}
