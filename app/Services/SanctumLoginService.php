<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SanctumLoginService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getToken($model, $tokenName)
    {
        return $this->createToken($model,$tokenName);
    }

    public function validateCredentials($request, $model,$tokenName)
    {
        if (! $model || ! Hash::check($request->password, $model->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $this->createToken($model,$tokenName);
    }


    private function createToken($model, $tokenName)
    {
        return $model->createToken($tokenName)->plainTextToken;
    }


}
