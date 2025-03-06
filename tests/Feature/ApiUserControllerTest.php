<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiUserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        list($this->user, $this->token) = $this->createUserAndToken();
    }

    public function test_unauthenticated_user_cannot_view_user_info()
    {
        $user = User::factory()->create();
        $this->getJson('api/users/'.$user->id)
        ->assertUnauthorized()
        ->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }

    public function test_authenticated_user_can_view_user_info()
    {
        $this->withHeader('Authorization', "Bearer ".$this->token)
        ->getJson('api/users/'.$this->user->id)
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
                'posts' => [
                    '*' => [
                        'id',
                        'title',
                        'body',
                        'user' => [
                            'name',
                            'email'
                        ]
                    ]
                ]
            ],
        ]);
    }

    public function test_authenticated_user_cannot_view_user_not_existed()
    {
        $this->withHeader('Authorization', "Bearer ".$this->token)
        ->getJson('api/users/1000')
        ->assertNotFound()
        ->assertJsonValidationErrors([
            'message' => 'User not found!'
        ]);
    }
}
