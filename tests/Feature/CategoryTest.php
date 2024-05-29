<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_category_can_be_created(): void
    {
        $categoryData = [
            'name' => fake()->name()
        ];

        $response = $this->postJson('api/categories/create', $categoryData);

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
        $category = Category::factory(4)->create();

        $response = $this->getJson("/api/categories/all");

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
        $category = Category::factory()->create();
        $response = $this->getJson("/api/categories/show/{$category->uuid}");
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'Category fetched successfully',
            'data' => [
                'id' => $category->uuid,
                'name' => $category->name,
                'created_at' => $category->created_at->toJson(),
                'updated_at' => $category->updated_at->toJson(),
            ],
            'status_code' => 200,
        ]);
    }

    public function test_category_can_be_updated(): void
    {
        $category = Category::factory()->create();
        $categoryData = [
            'name' => fake()->name()
        ];

        $response = $this->patchJson("api/categories/update/{$category->uuid}", $categoryData);
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
                'id' => $category->uuid,
                'name' => $categoryData['name'],
            ],
            'status_code' => 200,
        ]);
        $this->assertDatabaseHas('categories', $categoryData);
    }


    public function test_category_can_be_deleted(): void
    {
        $category = Category::factory()->create();
        $response = $this->deleteJson("api/categories/delete/{$category->uuid}");
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'Category deleted successfully',
            'status_code' => 200,
        ]);
    }

}
