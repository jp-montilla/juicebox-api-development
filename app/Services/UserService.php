<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserService
{
    public function findUser($id)
    {
        $user = $this->findUserById($id);
        return new UserResource($user);
    }

    private function findUserById($id)
    {
        $user = User::with('posts')->where('id', $id)->first();
        if (!$user) {
            throw new CustomException('User not found!', 404);
        }
        return $user;
    }
}
