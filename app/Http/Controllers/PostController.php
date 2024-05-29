<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Http\Services\PostService;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use ApiResponseTrait;

    public PostService $postService;
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $posts = $this->postService->allPosts();
        if (isset($posts['status']) && $posts['status'] === false) {
            return $this->errorResponse($posts['message'], '', 400);
        }
        return $this->successResponse(PostResource::collection($posts['data']), $posts['message'], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request): \Illuminate\Http\JsonResponse
    {
        $post = $this->postService->createPost($request);
        if (isset($post['status']) && $post['status'] === false) {
            return $this->errorResponse($post['message'], '', 400);
        }
        return $this->successResponse(new PostResource($post['data']), $post['message'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid): \Illuminate\Http\JsonResponse
    {
        $post = $this->postService->singlePost($uuid);
        if (isset($post['status']) && $post['status'] === false) {
            return $this->errorResponse($post['message'], '', 400);
        }
        return $this->successResponse(new PostResource($post['data']), $post['message'], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, string $uuid): \Illuminate\Http\JsonResponse
    {
        $post = $this->postService->updatePost($request, $uuid);
        if (isset($post['status']) && $post['status'] === false) {
            return $this->errorResponse($post['message'], '', 400);
        }
        return $this->successResponse(new PostResource($post['data']), $post['message'], 200);
    }

    public function updatePostStatus(string $uuid): \Illuminate\Http\JsonResponse
    {
        $post = $this->postService->changePostStatus($uuid);
        if (isset($post['status']) && $post['status'] === false) {
            return $this->errorResponse($post['message'], '', 400);
        }
        return $this->successResponse(new PostResource($post['data']), $post['message'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid): \Illuminate\Http\JsonResponse
    {
        $post = $this->postService->deletePost($uuid);
        if (isset($post['status']) && $post['status'] === false) {
            return $this->errorResponse($post['message'], '', 400);
        }
        return $this->successResponse($post['data'], $post['message'], 200);
    }
}
