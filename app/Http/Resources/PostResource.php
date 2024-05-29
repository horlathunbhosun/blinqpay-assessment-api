<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'post_title' => $this->title,
            'post_slug' => $this->slug,
            'post_content' => $this->content,
            'post_excerpt' => $this->excerpt,
            'post_status' => $this->status,
            'post_thumbnail' => $this->thumbnail,
            'post_main_image' => $this->main_image,
            'post_images' => json_decode($this->images),
            'post_category' => new CategoryResource($this->category),
            'post_author' => $this->author,
            'post_created_at' => $this->created_at,
            'post_updated_at' => $this->updated_at,
        ];
    }
}
