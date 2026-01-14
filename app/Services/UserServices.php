<?php
namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;

class UserServices {
    public function viewUserProfile() {
        $user = $this->getAuthenticatedUser();
        if(!$user) {
            return false;
        }
        return $user;
    }

    public function getAuthenticatedUser() {
        return JWTAuth::user();
    }

    public function updateUserProfile(array $data) {
        $user = $this->getAuthenticatedUser();
        if(!$user) {
            return false;
        }
        $user->update($data);
        return $user;
    }
}