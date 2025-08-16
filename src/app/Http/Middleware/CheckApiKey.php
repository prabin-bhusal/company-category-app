<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('x-api-key');

        if ($apiKey !== config('app.api_key')) {
            return response()->json(['error' => 'Invalid API Key'], 401);
        }

        return $next($request);
    }
}

