<?php

namespace App\Services;

use App\Interfaces\AuthenticationInterface;
use App\Models\User;

class AuthServices
{
    public function __construct(private AuthenticationInterface $authenticationInterface)
    {
        
    }

    public function register($request)
    {
        $user = User::create($request->validated());
        $token = $this->authenticationInterface->getToken($user, 'auth_token');
        return [$user, $token];
    }

    public function login($request)
    {
        $user = User::where('email', $request->email)->first();
        $token = $this->authenticationInterface->validateCredentials($request, $user, 'auth_token');
        return [$user, $token];
    }

    public function logout()
    {
        $this->authenticationInterface->logout();
    }
}
