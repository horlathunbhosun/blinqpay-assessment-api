<?php

namespace App\Http\Services;

use App\Http\Enums\GenericStatusEnum;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Str;

class PostService
{
    use ApiResponseTrait;

    public function allPosts(): array
    {
        try{
            $perPage = request()->perPage;
            $posts = Post::getPostWithRelationshipAndPagination($perPage);
            return $this->successObject($posts, 'Posts fetched successfully', 200);
        }catch (\Exception $e) {
            return $this->errorObject($e->getMessage());
        }
    }

    public function singlePost($uuid): array
    {
        try{
            $post = Post::where('uuid', $uuid)->with('author', 'category')->first();
            if (!$post) {
                return $this->errorObject('Post not found');
            }
            return $this->successObject($post, 'Post fetched successfully', 200);
        }catch (\Exception $e) {
            return $this->errorObject($e->getMessage());
        }
    }

    public function createPost($request): array
    {
        try{
            $category_id = Category::getCategoryWithUUID($request->category_id);
            $post = new Post();
            $post->title = $request->title;
            $post->slug = Str::slug($request->title);
            $post->post_content = $request->content;
            $post->category_id = $category_id->id;
            $post->author_id = auth()->user()->id??1;
            $post->status = GenericStatusEnum::ACTIVE;
            $post->save();
            return $this->successObject($post, 'Post created successfully', 201);
        }catch (\Exception $e) {
            return $this->errorObject($e->getMessage());
        }
    }

    public function updatePost($request, $uuid): array
    {
        try{
            $category_id = Category::getCategoryWithUUID($request->category_id);
            $post = Post::getPostWithUUID($uuid);
            if (!$post) {
                return $this->errorObject('Post not found');
            }
            $post->title = $request->title;
            $post->slug = Str::slug($request->title);
            $post->post_content = $request->content;
            $post->category_id = $category_id->id;
            $post->save();
            return $this->successObject($post, 'Post updated successfully', 200);
        }catch (\Exception $e) {
            return $this->errorObject($e->getMessage());
        }
    }

    public function changePostStatus($uuid): array
    {
        try{
            $post = Post::getPostWithUUID($uuid);
            if (!$post) {
                return $this->errorObject('Post not found');
            }
            $post->status = $post->status == GenericStatusEnum::ACTIVE ? GenericStatusEnum::INACTIVE : GenericStatusEnum::ACTIVE;
            $post->save();
            return $this->successObject($post, 'Post status changed successfully', 200);
        }catch (\Exception $e) {
            return $this->errorObject($e->getMessage());
        }
    }

    public function deletePost($uuid): array
    {
        try{
            $post = Post::getPostWithUUID($uuid);
            if (!$post) {
                return $this->errorObject('Post not found');
            }
            $post->delete();
            return $this->successObject([], 'Post deleted successfully', 200);
        }catch (\Exception $e) {
            return $this->errorObject($e->getMessage());
        }
    }

}
