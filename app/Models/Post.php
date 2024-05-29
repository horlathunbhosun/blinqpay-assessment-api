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

    protected $fillable = ['title', 'content', 'category_id'];


    public static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string)Str::uuid();
        });

        static::deleting(function ($model) {
            $model->author()->delete();
            $model->category()->delete();
        });
    }


    protected function casts(): array
    {
        return [
            'status' => GenericStatusEnum::class
        ];
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

    public static function getPostWithRelationshipAndPagination($perPage = 10)
    {
        return self::with('author', 'category')->orderBy('created_at', 'desc')->paginate($perPage);
    }

}
