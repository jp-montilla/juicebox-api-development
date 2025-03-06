<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\WelcomeEmailService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class SendWelcomeEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_send_email_job()
    {
        $this->mock(WelcomeEmailService::class, function (MockInterface $mock) {
            $mock->shouldReceive('sendWelcomeEmail')->once()->andReturn(true);
        });
        
        $user = User::factory()->create();
        $this->artisan('mail:send-welcome-email '.$user->email)->assertExitCode(0);
    }
}
