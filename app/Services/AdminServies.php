<?php
namespace App\Services;

use App\Models\User;

class AdminServies {
    public function viewUsers() {
        $users = User::where('role', 'user')->get();
        if($users->isEmpty()) {
            return false;
        }
        return $users;
    }
}