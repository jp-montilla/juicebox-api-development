<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserService
{
    public function findUser($id)
    {
        $user = $this->findUserWhere('id', $id);
        $this->checkUserExist($user);
        return new UserResource($user);
    }

    public function findUserByEmail($email)
    {
        $user = $this->findUserWhere('email', $email);
        return new UserResource($user);
    }

    private function findUserWhere($column, $id)
    {
        $user = User::with('posts')->where($column, $id)->first();
        return $user;
    }

    private function checkUserExist($user)
    {
        if (!$user) {
            throw new CustomException('User not found!', 404);
        }
    }
}
