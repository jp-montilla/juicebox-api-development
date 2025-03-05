<?php

namespace App\Services;

use App\Jobs\SendWelcomeEmail;

class WelcomeEmailService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function sendWelcomeEmail($user)
    {
        dispatch(new SendWelcomeEmail([
            'email' => $user->email,
            'name' => $user->name,
        ]));
    }
}
