<?php

namespace App\Http\Controllers\Api;

use App\Classes\ApiResponse;
use App\Classes\LoginResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\AuthenticationInterface;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct(private AuthenticationInterface $authenticationInterface)
    {
        
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        $token = $this->authenticationInterface->getToken($user, 'auth_token');
        return ApiResponse::sendResponse(new UserResource($user), 'User created!', 201, $token);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        $token = $this->authenticationInterface->validateCredentials($request, $user, 'auth_token');
        return ApiResponse::sendResponse(new UserResource($user), 'User logged in!', 200, $token);
    }
}
