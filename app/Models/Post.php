<?php

namespace App\Models;

use App\Http\Enums\GenericStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = ['title', 'excerpt', 'content', 'category_id', 'thumbnail', 'main_image', 'images', 'status', 'author_id'];


    public static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string)Str::uuid();
        });


    }


    protected function casts(): array
    {
        return [
            'status' => GenericStatusEnum::class,
            'images' => 'array'
        ];
    }


    public function postImages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PostImage::class);
    }


    public function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public static function getPostWithUUID($uuid)
    {
        return self::where('uuid', $uuid)->first();
    }
    public static function getPostWithUUIDAndRelationship($uuid)
    {
        return self::with('author', 'category')->where('uuid', $uuid)->first();
    }

    public static function getPostWithSlugAndRelationship($slug)
    {
        return self::with('author', 'category')->where('slug', $slug)->first();
    }

    public static function getAllPostByLoggedInUser($perPage = 10)
    {
        return self::with('author', 'category')
            ->where('author_id', auth()->id())->orderBy('created_at', 'desc')->paginate($perPage);
    }


    public static function getPostWithRelationshipAndPagination($category, $perPage = 10)
    {
        return self::with('author', 'category')->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public static function getPostWithCount()
    {
        return self::where('author_id', auth()->id())->count();
    }

}
