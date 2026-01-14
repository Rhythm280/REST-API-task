<?php

namespace App\Http\Controllers;

use App\Services\UserServices;

class UserController extends Controller
{
    protected $userServices;

    public function __construct(UserServices $userServices)
    {
        $this->userServices = $userServices;
    }

    public function viewUserProfile()
    {
        $user = $this->userServices->viewUserProfile();
        if(!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 404);
        }
        $user->load('collections');

        return response()->json([
            'status' => true,
            'message' => 'User profile fetched successfully',
            'data' => [
                'user' => $user,
            ]
        ]);
    }

    public function updateUserProfile(UpdateUserRequest $request) {
        $user  = $this->userServices->updateUserProfile($request->all());
        if(!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'User profile updated successfully',
        ], 200);
    }
}
