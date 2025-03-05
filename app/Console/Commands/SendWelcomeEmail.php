<?php

namespace App\Console\Commands;

use App\Services\UserService;
use App\Services\WelcomeEmailService;
use Illuminate\Console\Command;

class SendWelcomeEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send-welcome-email {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send welcome email.';

    /**
     * Execute the console command.
     */
    public function handle(WelcomeEmailService $welcomeEmailService, UserService $userService)
    {
        try {
            $welcomeEmailService->sendWelcomeEmail($userService->findUserByEmail($this->argument('email')));
        } catch (\Exception $e){
            $this->fail('User not found!'); 
        }
    }
}
