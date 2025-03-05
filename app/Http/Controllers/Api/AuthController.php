<?php

namespace App\Http\Controllers\Api;

use App\Classes\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthServices;
use Illuminate\Support\Facades\Request;

class AuthController extends Controller
{
    public function __construct(private AuthServices $authServices)
    {
        
    }

    public function register(RegisterRequest $request)
    {
        list($user, $token) = $this->authServices->register($request);
        return ApiResponse::sendResponse(new UserResource($user), 'User created!', 201, $token);
    }

    public function login(LoginRequest $request)
    {
        list($user, $token) = $this->authServices->login($request);
        return ApiResponse::sendResponse(new UserResource($user), 'User logged in!', 200, $token);
    }

    public function logout(Request $request)
    {
        $this->authServices->logout($request);
        return ApiResponse::sendResponse('', 'User logged out!', 200);
    }
}
