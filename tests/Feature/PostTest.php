<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $category;
    protected  $user;
    protected $post;
    public function setUp(): void
    {
        parent::setUp();
        // create a category for the post
        $this->category = Category::factory()->create();
        $this->user = User::factory()->create();
        $this->post = Post::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->user->id
        ]);

    }

    public function test_create_post(): void
    {
        // prepare payload data
        $postData = [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'category_id' => $this->category->uuid,
        ];

        $response = $this->postJson('/api/posts/create', $postData);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'status',
            'message',
            'data'=>[
                'id',
                'post_title',
                'post_slug',
                'post_content',
                'post_status',
                'post_category',
                'post_author',
                'post_created_at',
                'post_updated_at'

            ],
            'status_code'
        ]);
    }

    public function test_retrieve_all_posts(): void
    {
        $response = $this->getJson('/api/posts/all');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status',
            'message',
            'data'=>[
                    '*' => [
                        'id',
                        'post_title',
                        'post_slug',
                        'post_content',
                        'post_status',
                        'post_category',
                        'post_author',
                        'post_created_at',
                        'post_updated_at'
                    ]
                ],
            'status_code'
        ]);
    }

    public function test_retrieve_a_single_post(): void
    {
        $response = $this->getJson("/api/posts/show/{$this->post->uuid}");

        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'Post fetched successfully',
            'data' => [
                'id' => $this->post->uuid,
                'post_title' => $this->post->title,
                'post_slug' => $this->post->slug,
                'post_content' => $this->post->post_content,
                'post_status' => $this->post->status->value,
                'post_category' => [
                    'id' => $this->post->category->uuid,
                    'name' => $this->post->category->name,
                    'created_at' => $this->post->category->created_at->toJson(),
                    'updated_at' => $this->post->category->updated_at->toJson(),
                ],
                'post_author' => [
                    'id' => $this->post->author->id,
                    'uuid' => $this->post->author->uuid,
                    'name' => $this->post->author->name,
                    'email' => $this->post->author->email,
                    'email_verified_at' => $this->post->author->email_verified_at->toJson(),
                    'status' => $this->post->author->status->value,
                    'created_at' => $this->post->author->created_at->toJson(),
                    'updated_at' => $this->post->author->updated_at->toJson(),
                ],
                'post_created_at' => $this->post->created_at->toJson(),
                'post_updated_at' => $this->post->updated_at->toJson(),
            ],
            'status_code' => 200
        ]);
    }


    public function test_update_post(): void
    {

        $postData = [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'category_id' => $this->category->uuid,
        ];

        $response = $this->patchJson("/api/posts/update/{$this->post->uuid}", $postData);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data'=>[
                'id',
                'post_title',
                'post_slug',
                'post_content',
                'post_status',
                'post_category',
                'post_author',
                'post_created_at',
                'post_updated_at'

            ],
            'status_code'
        ]);
    }

    public function test_change_post_status(): void
    {
        $response = $this->patchJson("/api/posts/update-status/{$this->post->uuid}");

        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'Post status changed successfully',
            'status_code' => 200,
        ]);
    }
//
    public function test_delete_post(): void
    {
        $post = Post::factory()->create();

        $response = $this->deleteJson("/api/posts/delete/{$post->uuid}");

        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'Post deleted successfully',
            'status_code' => 200,
        ]);
    }
}
