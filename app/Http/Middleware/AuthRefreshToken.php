<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
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
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user not found'], 404);
            }
        } catch (TokenExpiredException $e) {
            try {
                $newToken = JWTAuth::refresh(JWTAuth::getToken());
                JWTAuth::setToken($newToken);

                // CRITICAL: Update the request header so subsequent middleware/controllers see the NEW token
                $request->headers->set('Authorization', 'Bearer ' . $newToken);

                $response = $next($request);

                // If the response is JSON, append the new token to the body
                if ($response instanceof JsonResponse) {
                    $responseData = $response->getData(true);
                    $responseData['new_token_refreshed'] = $newToken;
                    $response->setData($responseData);
                }

                return $response->header('Authorization', 'Bearer ' . $newToken);

            } catch (JWTException $refreshException) {
                return response()->json(['message' => 'token cannot be refreshed'], 401);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'token invalid'], 401);
        }

        return $next($request);
    }
}
