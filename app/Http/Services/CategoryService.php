<?php

namespace App\Http\Services;

use App\Http\Enums\GenericStatusEnum;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Category;

class CategoryService
{
    use ApiResponseTrait;
    public function createCategory($request): array
    {
        try {
            $category = new Category();
            $category->name = $request->name;
            $category->save();
            return $this->successObject($category, 'Category created successfully', 201);
        }catch (\Exception $e) {
            return $this->errorObject($e->getMessage());
        }

    }
    // Get category by id
    public function getCategory($uuid): array
    {
        try {
            $categoryData = Category::getCategoryWithUUID($uuid);
            if (!$categoryData) {
                return $this->errorObject('Category not found');
            }
            return $this->successObject($categoryData, 'Category fetched successfully', 200);
        }catch (\Exception $e) {
            return $this->errorObject($e->getMessage());
        }
    }

    // update category
    public function updateCategory($request, $uuid): array
    {
        try {
            $categoryData = Category::getCategoryWithUUID($uuid);
            if (!$categoryData) {
                return $this->errorObject('Category not found');
            }
            $categoryData->name = $request->name;
            $categoryData->save();
            return $this->successObject($categoryData, 'Category updated successfully', 200);
        }catch (\Exception $e) {
            return $this->errorObject($e->getMessage());
        }
    }

    // Delete category by id
    public function deleteCategory($uuid): array
    {
        try {
            $categoryData = Category::getCategoryWithUUID($uuid);
            if (!$categoryData) {
                return $this->errorObject('Category not found');
            }
            $categoryData->delete();
            return $this->successObject([], 'Category deleted successfully', 200);
        }catch (\Exception $e) {
            return $this->errorObject($e->getMessage());
        }
    }

    // Get all categories
    public function getCategories(): array
    {
        try {
            $categories = Category::all();
            return $this->successObject($categories, 'Categories fetched successfully', 200);
        }catch (\Exception $e) {
            return $this->errorObject($e->getMessage());
        }
    }






}
