<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiLoggerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Log Request
        Log::channel('api')->info('API Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'headers' => $request->headers->all(),
            'body' => $request->all(),
        ]);

        $response = $next($request);

        // Log Response
        Log::channel('api')->info('API Response', [
            'status' => $response->status(),
            'content' => $response->getContent(),
        ]);

        return $response;
    }
}
