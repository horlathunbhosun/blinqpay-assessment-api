<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        if (isset($this->resource['user'])) {
            $data = [
                'id' => $this->resource['user']['uuid'],
                'name' => $this->resource['user']['name'],
                'email' => $this->resource['user']['email'],
                'created_at' => $this->resource['user']['created_at'],
                'updated_at' => $this->resource['user']['updated_at'],
            ];
        } else {
            $data = [
                'id' => $this->uuid,
                'name' => $this->name,
                'email' => $this->email,
                'post' => PostResource::collection($this->resource['posts']),
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ];
        }
        // Check if additional keys exist and merge them
        if (isset($this->resource['token'])) {
            $data['token'] = $this->resource['token'];
        }

        if (isset($this->resource['token_type'])) {
            $data['token_type'] = $this->resource['token_type'];
        }

        return $data;
    }
}
