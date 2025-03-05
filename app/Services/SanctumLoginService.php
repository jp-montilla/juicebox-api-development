<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Interfaces\AuthenticationInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SanctumLoginService implements AuthenticationInterface
{
    public function getToken($model, $tokenName)
    {
        return $this->createToken($model,$tokenName);
    }

    public function validateCredentials($request, $model, $tokenName)
    {
        if (! $model || ! Hash::check($request->password, $model->password)) {
            throw new CustomException('The provided credentials are incorrect.', 401);
        }

        return $this->createToken($model,$tokenName);
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
    }


    private function createToken($model, $tokenName)
    {
        return $model->createToken($tokenName)->plainTextToken;
    }


}
