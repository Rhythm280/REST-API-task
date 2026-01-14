<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
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
                return response()->json([
                    'status' => true,
                    'message' => 'Token refreshed successfully',
                    'data' => [
                        'token' => $newToken,
                    ],
                ]);
            } catch(Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Token refreshed failed',
                ], 401);
            }
        }
        return $next($request);
    }
}
