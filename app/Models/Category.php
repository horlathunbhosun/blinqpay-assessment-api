<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['name'];

    public static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string)Str::uuid();
        });
    }

    public static function getCategoryWithUUID($uuid)
    {
        return self::where('uuid', $uuid)->first();
    }

    public static function getCategoryWithUUIDAndRelationship($uuid)
    {
        return self::with('posts')->where('uuid', $uuid)->first();
    }

    //get category count
    public static function getCategoryCount()
    {
        return self::count();
    }

    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class);
    }

}
