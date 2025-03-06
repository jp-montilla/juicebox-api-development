<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function createUserAndToken()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Authentification Token')->plainTextToken;

        return [$user, $token];
    }
}
