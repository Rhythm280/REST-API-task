<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user || $user->role !== $role) {
                return response()->json([
                    'status' => false,
                    'message' => 'You are not authorized to access this resource',
                ], 401);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Token is invalid or expired',
            ], 401);
        }
        return $next($request);
    }
}
