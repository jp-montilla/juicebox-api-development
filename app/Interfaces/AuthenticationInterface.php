<?php

namespace App\Interfaces;

interface AuthenticationInterface
{
    public function getToken($model, string $tokenName);
    public function validateCredentials($request, $model, string $tokenName);
}
