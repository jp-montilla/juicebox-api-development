<?php

namespace App\Http\Controllers\Api;

use App\Classes\LoginResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        $token = $user->createToken('auth_token')->plainTextToken;
        return LoginResponse::sendResponse($user, 'User created!', $token);
    }
}
