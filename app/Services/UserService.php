<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserService
{
    public function findUser($id)
    {   
        $user = Cache::remember("user_data_$id", 15, function () use ($id) {
            return $this->findUserWhere('id', $id);
        });
        $this->checkUserExist($user);
        return new UserResource($user);
    }

    public function findUserByEmail($email)
    {
        $user = Cache::remember("user_data_$email", 15, function () use ($email) {
            return $this->findUserWhere('email', $email);
        });
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
