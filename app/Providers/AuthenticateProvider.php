<?php

namespace App\Providers;

use App\Interfaces\AuthenticationInterface;
use App\Services\SanctumLoginService;
use Illuminate\Support\ServiceProvider;

class AuthenticateProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (config('constants.auth_provider') == 'sanctum') {
            $this->app->bind(AuthenticationInterface::class, SanctumLoginService::class);
        }
    }
}
