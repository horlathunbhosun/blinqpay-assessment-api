<?php

namespace App\Http\Services;

use App\Http\Enums\GenericStatusEnum;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostService
{
    use ApiResponseTrait;

    // Get all posts
    public function allPosts(): array
    {
        try{
            // Get the number of posts to be displayed per page
            $perPage = request()->perPage;
            // Get all posts with relationship and pagination
            $posts = Post::getPostWithRelationshipAndPagination($perPage);
            return $this->successObject($posts, 'Posts fetched successfully', 200);
        }catch (\Exception $e) {
            return $this->errorObject($e->getMessage());
        }
    }

    //Get all post count
    public function allPostsCount(): array
    {
        try{
            // Get all posts count
            $posts = Post::getPostWithCount();
            $category = Category::getCategoryCount();
            return $this->successObject(['category_count' => $category, 'posts_count' => $posts], 'Posts count fetched successfully', 200);
        }catch (\Exception $e) {
            return $this->errorObject($e->getMessage());
        }
    }

    // Get all posts by author

    public function allPostsByAuthor(): array
    {
        try{
            // Get the number of posts to be displayed per page
            $perPage = request()->perPage;
            // Get all posts with relationship and pagination
            $posts = Post::getAllPostByLoggedInUser($perPage);
            return $this->successObject($posts, 'Posts fetched successfully', 200);
        }catch (\Exception $e) {
            return $this->errorObject($e->getMessage());
        }
    }

    // Get single post
    public function singlePost($slug): array
    {
        try{
            // Get post with relationship
            $post = Post::getPostWithSlugAndRelationship($slug);
            // Check if post exists
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
            // Get category id
            $category_id = Category::getCategoryWithUUID($request->category_id);

            // Create post instance
            $post = new Post();
            $post->title = $request->post_title;
            $post->slug = Str::slug($request->post_title);
            $post->excerpt = $request->post_excerpt;
            $post->content = $request->post_content;
            $post->category_id = $category_id->id;
            $post->author_id = Auth::id();
            $post->status = GenericStatusEnum::ACTIVE;

            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $thumbnailName = Str::uuid() . '.' . $thumbnail->getClientOriginalExtension();
                $thumbnailPath = $thumbnail->storeAs('thumbnails', $thumbnailName, 'public');
            }

            if ($request->hasFile('main_image')) {
                $mainImage= $request->file('main_image');
                $mainImageName = Str::uuid() . '.' . $mainImage->getClientOriginalExtension();
                $mainImagePath = $mainImage->storeAs('main_images', $mainImageName, 'public');
            }

            $images = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                    $images[] = $image->storeAs('images', $imageName, 'public');
                }
            }
            $post->thumbnail = $thumbnailPath ?? null;
            $post->main_image = $mainImagePath ?? null;
            $post->images = json_encode($images);
            $post->save();

            // Return success response
            return $this->successObject($post, 'Post created successfully', 201);
        }catch (\Exception $e) {
            return $this->errorObject($e->getMessage());
        }
    }

    public function updatePost($request, $uuid): array
    {
        try{
            // Get category id
            $category_id = Category::getCategoryWithUUID($request->category_id);
            // Get post with uuid
            $post = Post::getPostWithUUID($uuid);
            // Check if post exists
            if (!$post) {
                return $this->errorObject('Post not found');
            }
            $title = $request->post_title ?? $post->title;
            // Update post
            $post->title = $title;
            $post->slug = Str::slug($title);
            $post->excerpt = $request->post_excerpt ?? $post->excerpt;
            $post->content = $request->post_content ?? $post->content;
            $post->category_id = $category_id->id;
            if ($request->hasFile('thumbnail')) {
                // Delete the old thumbnail if exists
                if ($post->thumbnail) {
                    Storage::disk('public')->delete($post->thumbnail);
                }

                $thumbnail = $request->file('thumbnail');
                $thumbnailName = Str::uuid() . '.' . $thumbnail->getClientOriginalExtension();
                $thumbnailPath = $thumbnail->storeAs('thumbnails', $thumbnailName, 'public');
                $post->thumbnail = $thumbnailPath;
            }

            if ($request->hasFile('main_image')) {
                // Delete the old main image if exists
                if ($post->main_image) {
                    Storage::disk('public')->delete($post->main_image);
                }

                $mainImage = $request->file('main_image');
                $mainImageName = Str::uuid() . '.' . $mainImage->getClientOriginalExtension();
                $mainImagePath = $mainImage->storeAs('main_images', $mainImageName, 'public');
                $post->main_image = $mainImagePath;
            }

            if ($request->hasFile('images')) {
                // Delete old images if exists
                if ($post->images) {
                    foreach (json_decode($post->images) as $imagePath) {
                        Storage::disk('public')->delete($imagePath);
                    }
                }

                $images = [];
                foreach ($request->file('images') as $image) {
                    $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                    $images[] = $image->storeAs('images', $imageName, 'public');
                }
                $post->images = json_encode($images);
            }


            $post->save();
            return $this->successObject($post, 'Post updated successfully', 200);
        }catch (\Exception $e) {
            // Return error response
            return $this->errorObject($e->getMessage());
        }
    }

    public function changePostStatus($uuid): array
    {
        try{
            // Get post with uuid
            $post = Post::getPostWithUUID($uuid);
            if (!$post) {
                return $this->errorObject('Post not found');
            }
            // Change post status
            $post->status = $post->status == GenericStatusEnum::ACTIVE ? GenericStatusEnum::INACTIVE : GenericStatusEnum::ACTIVE;
            $post->save();

            // Return success response
            return $this->successObject($post, 'Post status changed successfully', 200);
        }catch (\Exception $e) {
            // Return error response
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
