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
            $category->status = GenericStatusEnum::ACTIVE;
            $category->save();
            return $this->successObject($category, 'Category created successfully', 201);
        }catch (\Exception $e) {
            return $this->errorObject($e->getMessage());
        }

    }

    // update category
    public function updateCategory($request, $category): array
    {
        try {
            $categoryData = Category::find($category->id);
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
    public function deleteCategory($category): array
    {
        try {
            $categoryData = Category::find($category->id);
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

    // activate and deactivate category status
    public function updateStatus($request,$category): array
    {
        try {
            $categoryData = Category::find($category->id);
            if (!$categoryData) {
                return $this->errorObject('Category not found');
            }
            if ($request->status == GenericStatusEnum::ACTIVE) {
                $categoryData->status = GenericStatusEnum::ACTIVE;
                $categoryData->save();
                return $this->successObject($categoryData, 'Category activated successfully', 200);
            }

            $categoryData->status = GenericStatusEnum::INACTIVE;
            $categoryData->save();
            return $this->successObject($categoryData, 'Category deactivated successfully', 200);
        }catch (\Exception $e) {
            return $this->errorObject($e->getMessage());
        }
    }




}
