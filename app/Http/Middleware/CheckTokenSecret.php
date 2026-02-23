<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenSecret
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('token_secret');

        $envToken = config('api.token_secret');

        if (! $token || $token !== $envToken) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        return $next($request);
    }
}
