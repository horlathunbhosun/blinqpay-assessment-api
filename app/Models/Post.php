<?php

namespace App\Models;

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
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
