<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiPostControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        list($this->user, $this->token) = $this->createUserAndToken();
    }

    public function test_unauthenticated_user_cant_access_post_route()
    {
        $this->getJson('api/posts/')
        ->assertUnauthorized()
        ->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }

    public function test_authenticated_user_can_view_posts_list()
    {
        User::factory()->hasPosts(30)->create();
        $this->withHeader('Authorization', "Bearer ".$this->token)
        ->getJson('api/posts/')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'body',
                    'user' => [
                        'name',
                        'email',
                    ],
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
    }

    public function test_authenticated_user_can_view_posts_list_other_page()
    {
        User::factory()->hasPosts(30)->create();
        $this->withHeader('Authorization', "Bearer ".$this->token)
        ->getJson('api/posts/?page=2')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'body',
                    'user' => [
                        'name',
                        'email',
                    ],
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
    }

    public function test_authenticated_user_can_view_single_post()
    {
        User::factory()->hasPosts(2)->create();
        $this->withHeader('Authorization', "Bearer ".$this->token)
        ->getJson('api/posts/1')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'body',
                'user' => [
                    'name',
                    'email',
                ],
                'created_at',
                'updated_at',
            ]
        ]);
    }

    public function test_authenticated_user_cant_create_post_without_title()
    {
        $this->withHeader('Authorization', "Bearer ".$this->token)
        ->postJson('api/posts', [
            'body' => fake()->paragraph(),
        ])->assertUnprocessable()
        ->assertJsonValidationErrors([
            'title' => 'The title field is required.'
        ]);
    }

    public function test_autheticathed_user_cant_create_post_with_existing_title()
    {
        $post = Post::factory()->count(1)->for($this->user)->create();
        $this->withHeader('Authorization', "Bearer ".$this->token)
        ->postJson('api/posts', [
            'title' => $post[0]->title,
            'body' => fake()->paragraph(),
            'user_id' => $this->user->id,
        ])->assertUnprocessable()
        ->assertJsonValidationErrors([
            'title' => 'The title has already been taken.'
        ]);
    }

    public function test_authenticated_user_cant_create_post_with_title_length_more_than_fifty()
    {
        $this->withHeader('Authorization', "Bearer ".$this->token)
        ->postJson('api/posts', [
            'title' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam id turpis mattis, mollis eros id, placerat ipsum. Integer congue, felis sit amet vehicula dapibus, felis orci volutpat risus',
            'body' => fake()->paragraph(),
            'user_id' => $this->user->id,
        ])->assertUnprocessable()
        ->assertJsonValidationErrors([
            'title' => 'The title field must not be greater than 50 characters.'
        ]);
    }

    public function test_authenticated_user_can_create_post_with_valid_data()
    {
        $this->withHeader('Authorization', "Bearer ".$this->token)
        ->postJson('api/posts', [
            'title' => 'Unique title',
            'body' => 'Body Testing',
        ])->assertStatus(201)
        ->assertJson([
            'message' => 'Post created!'
        ]);
        $this->assertDatabaseHas('posts', [
            'title' => 'Unique title',
            'body' => 'Body Testing',
        ]);
    }

    public function test_authenticated_user_cant_update_post_of_other_user()
    {
        $user = User::factory()->create();
        $post = Post::factory()->count(1)->for($user)->create();
        $this->withHeader('Authorization', "Bearer ".$this->token)
        ->patchJson('api/posts/'.$post[0]->id, [
            'title' => 'Updated Unique Title',
            'body' => fake()->paragraph()
        ])->assertForbidden()
        ->assertJsonValidationErrors([
            'message' => 'You do not owned this post!.'
        ]);
    }

    public function test_authenticated_user_cant_update_non_existing_post()
    {
        $this->withHeader('Authorization', "Bearer ".$this->token)
        ->patchJson('api/posts/5000', [
            'title' => 'Updated Unique Title',
            'body' => fake()->paragraph()
        ])->assertNotFound()
        ->assertJsonValidationErrors([
            'message' => 'Post not found!'
        ]);
    }

    public function test_authenticated_user_can_update_own_post()
    {
        $post = Post::factory()->count(1)->for($this->user)->create();
        $this->withHeader('Authorization', "Bearer ".$this->token)
        ->patchJson('api/posts/'.$post[0]->id, [
            'title' => 'Updated Unique title',
            'body' => $post[0]->body
        ])->assertStatus(200)
        ->assertJson([
            'message' => 'Post updated!',
        ])
        ->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'body',
                'user' => [
                    'name',
                    'email',
                ],
                'created_at',
                'updated_at',
            ]
        ]);
        $this->assertDatabaseHas('posts', [
            'title' => 'Updated Unique title',
        ]);
    }

    public function test_authenticated_user_cant_delete_post_of_other_user()
    {
        $user = User::factory()->create();
        $post = Post::factory()->count(1)->for($user)->create();
        $this->withHeader('Authorization', "Bearer ".$this->token)
        ->deleteJson('api/posts/'.$post[0]->id)
        ->assertForbidden()
        ->assertJsonValidationErrors([
            'message' => 'You do not owned this post!.'
        ]);
    }

    public function test_authenticated_user_cant_delete_non_existing_post()
    {
        $this->withHeader('Authorization', "Bearer ".$this->token)
        ->deleteJson('api/posts/5000')
        ->assertNotFound()
        ->assertJsonValidationErrors([
            'message' => 'Post not found!'
        ]);
    }

    public function test_authenticated_user_can_delete_own_post()
    {
        $post = Post::factory()->count(1)->for($this->user)->create();
        $this->withHeader('Authorization', "Bearer ".$this->token)
        ->deleteJson('api/posts/'.$post[0]->id)
        ->assertStatus(200)
        ->assertJson([
            'message' => 'Post deleted!'
        ]);
        $this->assertDatabaseMissing('posts', [
            'title' => $post[0]->title,
        ]);
    }

}
