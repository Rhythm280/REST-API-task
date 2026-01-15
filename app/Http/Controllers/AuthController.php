<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\AuthServices;

class AuthController extends Controller
{
    protected $authServices;

    public function __construct(AuthServices $authServices)
    {
        $this->authServices = $authServices;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authServices->register($request->all());
        return response()->json([
            'status' => true,
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
            ],
        ]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->all();
        $token = $this->authServices->login($credentials);
        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }
        $user = $this->authServices->getAuthenticatedUser();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'access_token' => $token,
            ],
        ]);
    }

    public function logout()
    {
        $logout = $this->authServices->logout();
        if (!$logout) {
            return response()->json([
                'status' => false,
                'message' => 'Logout failed',
            ], 400);
        }
        return response()->json([
            'status' => true,
            'message' => 'Logout successful',
        ], 200);
    }
}
