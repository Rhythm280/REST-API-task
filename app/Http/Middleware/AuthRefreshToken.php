<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

use Tymon\JWTAuth\Facades\JWTAuth;

class AuthRefreshToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch(Exception $e) {
            try {
                $newToken = JWTAuth::refresh(JWTAuth::getToken());
                $request->headers->set('Authorization', 'Bearer ' . $newToken);
                JWTAuth::setToken($newToken);
                return $next($request);
            } catch(Exception $refreshException) {
                return response()->json([
                    'status' => false,
                    'message' => 'Token refresh failed',
                ], 401);
            }
            Log::error($e->getMessage());
        }
        return $next($request);
    }
}
