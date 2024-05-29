<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status'];

    public static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string)Str::uuid();
        });
    }
}
