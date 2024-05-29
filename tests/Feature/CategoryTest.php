<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;



    protected $token;

    protected $category;

    protected $categories;

    public function setUp(): void
    {

        parent::setUp();

        // create a category for the post
        $this->category = Category::factory()->create();
        $this->categories = Category::factory(4)->create();

        $user = User::factory()->create();
        $credentials = [
            'email' => $user->email,
            'password' => 'password',
        ];
        $response = $this->json('POST', '/api/auth/login', $credentials);
        $this->token = $response->json()['data']['token'];

    }

    public function test_category_can_be_created(): void
    {

        $categoryData = [
            'name' => fake()->name()
        ];

        $response = $this->postJson('api/categories/create', $categoryData, ['Authorization' => 'Bearer ' . $this->token]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'status',
            'message',
            'data'=>[
                'id',
                'name',
                'created_at',
                'updated_at'
            ],
            'status_code'
        ]);
        $this->assertDatabaseHas('categories', $categoryData);
    }

    public function test_category_can_be_retrieved(): void
    {
        $response = $this->getJson("/api/categories/all", ['Authorization' => 'Bearer ' . $this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data'=>[
                '*' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at'
                ]
            ],
            'status_code'
        ]);
    }

    public function test_single_category_can_be_retrieved(): void
    {
//        $category = Category::factory()->create();
        $response = $this->getJson("/api/categories/show/{$this->category->uuid}", ['Authorization' => 'Bearer ' . $this->token]);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'Category fetched successfully',
            'data' => [
                'id' => $this->category->uuid,
                'name' => $this->category->name,
                'created_at' => $this->category->created_at->toJson(),
                'updated_at' => $this->category->updated_at->toJson(),
            ],
            'status_code' => 200,
        ]);
    }
//
    public function test_category_can_be_updated(): void
    {


        $categoryData = [
            'name' => fake()->name()
        ];

        $response = $this->patchJson("api/categories/update/{$this->category->uuid}", $categoryData, ['Authorization' => 'Bearer ' . $this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data'=>[
                'id',
                'name',
                'created_at',
                'updated_at'
            ],
            'status_code'
        ]);
        $response->assertJson([
            'status' => true,
            'message' => 'Category updated successfully',
            'data' => [
                'id' => $this->category->uuid,
                'name' => $categoryData['name'],
            ],
            'status_code' => 200,
        ]);
        $this->assertDatabaseHas('categories', $categoryData);
    }


    public function test_category_can_be_deleted(): void
    {

        $response = $this->deleteJson("api/categories/delete/{$this->category->uuid}",[],['Authorization' => 'Bearer ' . $this->token]);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'Category deleted successfully',
            'status_code' => 200,
        ]);
    }

}
