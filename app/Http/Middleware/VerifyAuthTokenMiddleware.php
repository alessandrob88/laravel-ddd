<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyAuthTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('x-auth-token');

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        if ($token !== env('X_AUTH_TOKEN_VALUE')) {
            return response()->json(['error' => 'Bad Request'], 400);
        }

        // Perform additional verification or validation here if needed

        return $next($request);
    }
}
