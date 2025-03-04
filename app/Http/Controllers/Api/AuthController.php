<?php

namespace App\Http\Controllers\Api;

use App\Classes\LoginResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\SanctumLoginService;

class AuthController extends Controller
{
    public function __construct(private SanctumLoginService $loginService)
    {
        
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        $token = $this->loginService->getToken($user, 'auth_token');
        return LoginResponse::sendResponse(new UserResource($user), 'User created!', $token);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        $token = $this->loginService->validateCredentials($request, $user, 'auth_token');
        return LoginResponse::sendResponse(new UserResource($user), 'User logged in!', $token);
    }
}
