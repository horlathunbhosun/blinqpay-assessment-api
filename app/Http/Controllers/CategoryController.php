<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Services\CategoryService;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponseTrait;
    public CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $categories = $this->categoryService->getCategories();
        if (isset($categories['status']) && $categories['status'] === false) {
            return $this->errorResponse($categories['message'],400);
        }
        return $this->successResponse(CategoryResource::collection($categories['data']), $categories['message'], $categories['statusCode']);
    }

    //get category by uuid

    public function show(string $uuid): \Illuminate\Http\JsonResponse
    {
        $category = $this->categoryService->getCategory($uuid);
        if (isset($category['status']) && $category['status'] === false) {
            return $this->errorResponse($category['message'],400);
        }
        return $this->successResponse(new CategoryResource($category['data']), $category['message'], $category['statusCode']);
    }


    //get posts counts for auth user

    public function showCategoryCount(): \Illuminate\Http\JsonResponse
    {
        $category = $this->categoryService->getCategoriesCount();
        if (isset($category['status']) && $category['status'] === false) {
            return $this->errorResponse($category['message'],400);
        }
        return $this->successResponse(new CategoryResource($category['data']), $category['message'], $category['statusCode']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request): \Illuminate\Http\JsonResponse
    {
        $category = $this->categoryService->createCategory($request);
        if (isset($category['status']) && $category['status'] === false) {
            return $this->errorResponse($category['message'], "",400);
        }
        return $this->successResponse(new CategoryResource($category['data']), $category['message'], $category['statusCode']);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $uuid): \Illuminate\Http\JsonResponse
    {
        $categoryUpdate = $this->categoryService->updateCategory($request, $uuid);
        if (isset($categoryUpdate['status']) && $categoryUpdate['status'] === false) {
            return $this->errorResponse($categoryUpdate['message'],"", 400);
        }
        return $this->successResponse(new CategoryResource($categoryUpdate['data']), $categoryUpdate['message'], $categoryUpdate['statusCode']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid): \Illuminate\Http\JsonResponse
    {
        $categoryDelete = $this->categoryService->deleteCategory($uuid);
        if (isset($categoryDelete['status']) && $categoryDelete['status'] === false) {
            return $this->errorResponse($categoryDelete['message'],"", 400);
        }
        return $this->successResponse([], $categoryDelete['message'], $categoryDelete['statusCode']);
    }
}
