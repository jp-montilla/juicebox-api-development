<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\WelcomeEmailService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class ApiAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_cant_register_without_name(): void
    {
        $this->postJson('api/register', [
            'email' => fake()->unique()->safeEmail(),
            'password' => 'testpassword',
            'password_confirmation' => 'testpassword',
        ])->assertUnprocessable()
        ->assertJsonValidationErrors(['name' => 'The name field is required.']);
    }

    public function test_cant_register_without_email(): void
    {
        $this->postJson('api/register', [
            'name' => fake()->name(),
            'password' => 'testpassword',
            'password_confirmation' => 'testpassword',
        ])->assertUnprocessable()
        ->assertJsonValidationErrors(['email' => 'The email field is required.']);
    }

    public function test_cant_register_without_matching_password_confirmation(): void
    {
        $this->postJson('api/register', [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'testpassword',
            'password_confirmation' => 'testpassword2',
        ])->assertUnprocessable()
        ->assertJsonValidationErrors(['password' => 'The password field confirmation does not match.']);
    }

    public function test_cant_register_with_existing_email(): void
    {
        User::factory()->create(['email' => 'mytestemail@email.com']);

        $this->postJson('api/register', [
            'name' => fake()->name(),
            'email' => 'mytestemail@email.com',
            'password' => 'testpassword',
            'password_confirmation' => 'testpassword',
        ])->assertUnprocessable()
        ->assertJsonValidationErrors(['email' => 'The email has already been taken.']);
    }

    public function test_cant_register_with_password_length_less_than_six()
    {
        $this->postJson('api/register', [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'passs',
            'password_confirmation' => 'passs',
        ])->assertUnprocessable()
        ->assertJsonValidationErrors(['password' => 'The password field must be at least 6 characters.']);
    }

    public function test_can_register_with_valid_input_and_receive_token()
    {
        $this->mock(WelcomeEmailService::class, function (MockInterface $mock) {
            $mock->shouldReceive('sendWelcomeEmail')->once()->andReturn(true);
        });
        
        $this->postJson('api/register', [
            'name' => 'Valid Name',
            'email' => 'validemail@email.com',
            'password' => 'testpassword',
            'password_confirmation' => 'testpassword',
        ])->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at'
            ],
            'message',
            'token'
        ])
        ->assertJson([
            'message' => 'User created!'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Valid Name',
            'email' => 'validemail@email.com',
        ]);
    }

    public function test_cant_login_without_account()
    {
        $this->postJson('api/login', [
            'email' => fake()->unique()->safeEmail(),
            'password' => 'randompass',
        ])->assertUnauthorized()
        ->assertJsonValidationErrors(['message' => 'The provided credentials are incorrect.']);
    }

    public function test_cant_login_without_email()
    {
        $this->postJson('api/login', [
            'password' => 'randompass',
        ])->assertUnprocessable()
        ->assertJsonValidationErrors(['email' => 'The email field is required.']);
    }

    public function test_can_login_with_correct_credentials()
    {
        $user = User::factory()->create(['password' => 'password']);

        $this->postJson('api/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at'
            ],
            'message',
            'token'
        ])
        ->assertJson([
            'message' => 'User logged in!'
        ]);
    }

    public function test_cant_logout_unauthenticated_user()
    {
        $this->postJson('api/logout')
        ->assertUnauthorized()
        ->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }

    public function test_can_logout_authenticated_user()
    {
        $user = User::factory()->create();

        $token = $user->createToken('Authentification Token')->plainTextToken;

        $this->withHeader('Authorization', "Bearer $token")
        ->postJson('api/logout')
        ->assertStatus(200)
        ->assertJson([
            'message' => 'User logged out!'
        ]);
    }
}
