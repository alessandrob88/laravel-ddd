<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
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
            return response()->json([
                'success' => false,
                'message' => HttpResponse::$statusTexts[HttpResponse::HTTP_UNAUTHORIZED],
            ], HttpResponse::HTTP_UNAUTHORIZED);
        }
        
        if ($token !== env('X_AUTH_TOKEN_VALUE')) {
            return response()->json([
                'success' => false, 
                'message' => HttpResponse::$statusTexts[HttpResponse::HTTP_BAD_REQUEST],
            ], HttpResponse::HTTP_BAD_REQUEST);
        }

        // Perform additional verification or validation here if needed

        return $next($request);
    }
}
