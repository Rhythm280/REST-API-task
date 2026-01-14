<?php

namespace App\Http\Controllers;

use App\Services\AdminServies;

class AdminController extends Controller
{
    protected $adminServices;

    public function __construct(AdminServies $adminServices)
    {
        $this->adminServices = $adminServices;
    }

    public function viewUsers() {
        $users = $this->adminServices->viewUsers();
        if(!$users) {
            return response()->json([
                'status' => false,
                'message' => 'No users found',
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Users fetched successfully',
            'data' => [
                'users' => $users
            ]
        ]);
    }
}
