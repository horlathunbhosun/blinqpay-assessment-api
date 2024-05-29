<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $category;
    protected  $user;
    protected $post;
    protected $token;
    public function setUp(): void
    {
        parent::setUp();
        // create a category for the post
        $this->category = Category::factory()->create();
        $this->user = User::factory()->create();
        // create a post
        $this->post = Post::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->user->id
        ]);

        $credentials = [
            'email' => $this->user->email,
            'password' => 'password',
        ];
        $response = $this->json('POST', '/api/auth/login', $credentials);
        $this->token = $response->json()['data']['token'];

    }

    public function test_create_post(): void
    {
        //fake files mock
        $thumbnailFile = UploadedFile::fake()->image('thumbnail.jpg');
        $mainImageFile = UploadedFile::fake()->image('main_image.jpg');
        $additionalImages = [
            UploadedFile::fake()->image('image1.jpg'),
            UploadedFile::fake()->image('image2.jpg'),
            UploadedFile::fake()->image('image3.jpg'),
            UploadedFile::fake()->image('image4.jpg'),
            UploadedFile::fake()->image('image5.jpg'),
        ];

        // prepare payload data
        $postData = [
            'post_title' => $this->faker->title,
            'post_content' => $this->faker->paragraph,
            'post_excerpt' => $this->faker->sentence,
            'thumbnail' => $thumbnailFile,
            'main_image' => $mainImageFile,
            'images' => $additionalImages,
            'category_id' => $this->category->uuid,
        ];

        // mock the storage
        Storage::fake('public');

        $response = $this->postJson('/api/posts/create', $postData, ['Authorization' => 'Bearer ' . $this->token]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'status',
            'message',
            'data'=>[
                'id',
                'post_title',
                'post_slug',
                'post_excerpt',
                'post_content',
                'post_status',
                'post_thumbnail',
                'post_main_image',
                'post_images',
                'post_category',
                'post_author',
                'post_created_at',
                'post_updated_at'

            ],
            'status_code'
        ]);

        Storage::disk('public')->assertExists($response->json()['data']['post_thumbnail']);
        Storage::disk('public')->assertExists($response->json()['data']['post_main_image']);
        foreach ($response->json()['data']['post_images'] as $image) {
            Storage::disk('public')->assertExists($image);
        }
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
                        'post_excerpt',
                        'post_status',
                        'post_thumbnail',
                        'post_main_image',
                        'post_images',
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
        $response = $this->getJson("/api/posts/show/{$this->post->uuid}", ['Authorization' => 'Bearer ' . $this->token] );


        $response->assertStatus(200);

        $response->assertJson([
            'status' => true,
            'message' => 'Post fetched successfully',
            'data' => [
                'id' => $this->post->uuid,
                'post_title' => $this->post->title,
                'post_slug' => $this->post->slug,
                'post_content' => $this->post->content,
                'post_excerpt' => $this->post->excerpt,
                'post_status' => $this->post->status->value,
                'post_thumbnail' => $this->post->thumbnail,
                'post_main_image' => $this->post->main_image,
                'post_images' => json_decode($this->post->images),
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


        //fake files mock
        $thumbnailFile = UploadedFile::fake()->image('thumbnailupdated.jpg');
        $mainImageFile = UploadedFile::fake()->image('main_imageupdated.jpg');
        $additionalImages = [
            UploadedFile::fake()->image('image1updated.jpg'),
            UploadedFile::fake()->image('image2updated.jpg'),
            UploadedFile::fake()->image('image3updated.jpg'),
            UploadedFile::fake()->image('image4updated.jpg'),
            UploadedFile::fake()->image('image5updated.jpg'),
        ];

        // prepare payload data
        $postData = [
            'post_title' => $this->faker->title,
            'post_content' => $this->faker->paragraph,
            'post_excerpt' => $this->faker->sentence,
            'thumbnail' => $thumbnailFile,
            'main_image' => $mainImageFile,
            'images' => $additionalImages,
            'category_id' => $this->category->uuid,

        ];

        // mock the storage
        Storage::fake('public');

        $response = $this->patchJson("/api/posts/update/{$this->post->uuid}", $postData, ['Authorization' => 'Bearer ' . $this->token]);


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

        Storage::disk('public')->assertExists($response->json()['data']['post_thumbnail']);
        Storage::disk('public')->assertExists($response->json()['data']['post_main_image']);
        foreach ($response->json()['data']['post_images'] as $image) {
            Storage::disk('public')->assertExists($image);
        }

    }

    public function test_change_post_status(): void
    {
        $response = $this->patchJson("/api/posts/update-status/{$this->post->uuid}", [], ['Authorization' => 'Bearer ' . $this->token]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'Post status changed successfully',
            'status_code' => 200,
        ]);
    }

    public function test_delete_post(): void
    {

        $response = $this->deleteJson("/api/posts/delete/{$this->post->uuid}", [], ['Authorization' => 'Bearer ' . $this->token]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'Post deleted successfully',
            'status_code' => 200,
        ]);
    }
}
