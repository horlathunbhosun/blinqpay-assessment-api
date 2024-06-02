<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function (){
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

});

// Category Routes

Route::get('posts/all',[PostController::class,'index']);
Route::get('categories/all',[CategoryController::class,'index']);

Route::middleware('auth:sanctum')->group(function (){

    Route::prefix('categories')->group(function (){
        Route::post('/create',[CategoryController::class,'store']);
        Route::get('/show/{uuid}',[CategoryController::class,'show']);
        Route::patch('/update/{uuid}',[CategoryController::class,'update']);
        Route::delete('/delete/{uuid}',[CategoryController::class,'destroy']);
        Route::get('/category-count',[CategoryController::class,'showCategoryCount']);
    });
    // Post Routes
    Route::prefix('posts')->group(function (){
        Route::post('/create',[PostController::class,'store']);
        Route::get('/user',[PostController::class,'allPostByAuthor']);
        Route::get('/count',[PostController::class,'postCount']);
        Route::get('/show/{slug}',[PostController::class,'show']);
        Route::patch('/update/{uuid}',[PostController::class,'update']);
        Route::delete('/delete/{uuid}',[PostController::class,'destroy']);
        Route::patch('/update-status/{uuid}',[PostController::class,'updatePostStatus']);
    });

});





