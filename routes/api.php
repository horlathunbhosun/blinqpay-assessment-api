<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Category Routes
Route::prefix('categories')->group(function (){
    Route::post('/create',[CategoryController::class,'store']);
    Route::get('/all',[CategoryController::class,'index']);
    Route::get('/show/{uuid}',[CategoryController::class,'show']);
    Route::patch('/update/{uuid}',[CategoryController::class,'update']);
    Route::delete('/delete/{uuid}',[CategoryController::class,'destroy']);
});

// Post Routes
Route::prefix('posts')->group(function (){
    Route::post('/create',[PostController::class,'store']);
    Route::get('/all',[PostController::class,'index']);
    Route::get('/show/{uuid}',[PostController::class,'show']);
    Route::patch('/update/{uuid}',[PostController::class,'update']);
    Route::delete('/delete/{uuid}',[PostController::class,'destroy']);
    Route::patch('/update-status/{uuid}',[PostController::class,'updatePostStatus']);
});

