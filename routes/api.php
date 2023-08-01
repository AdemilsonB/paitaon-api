<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FollowersController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the 'api' middleware group. Enjoy building your API!
|
*/

//AUTH
Route::prefix('auth')->middleware('auth', ['except' => ['login']])->group(function(){
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

//USER
Route::prefix('users')->middleware('auth', ['except' => ['register']])->group(function () {
    Route::get('', [UserController::class, 'list']);
    Route::get('/me', [UserController::class, 'me']);
    Route::post('', [UserController::class, 'register']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'delete']);
});

//POST
Route::prefix('posts')->middleware('auth')->group(function(){
    Route::get('', [PostController::class, 'list']);
    Route::post('', [PostController::class, 'create']);
    Route::put('/{id}', [PostController::class, 'update']);
    Route::delete('/{id}', [PostController::class, 'delete']);

    Route::post('{post}/addComent', [PostController::class,'addComent']);
    Route::delete('{postId}/deleteComent/{comentId}', [PostController::class,'deleteComent']);
});

//LIKE
Route::prefix('likes')->middleware('auth')->group(function(){
    Route::post('/like/{id}', [LikeController::class, 'like'])->middleware('auth');
    Route::post('/dislike/{id}', [LikeController::class, 'dislike']);
    Route::post('/countLikes/{id}', [LikeController::class, 'countLikes']);
    Route::post('/countDislikes/{id}', [LikeController::class, 'countDislikes']);
});

//FOLLOWER
Route::prefix('follower')->middleware('auth')->group(function(){
    Route::get('', [FollowersController::class, 'countFollowers']);
    Route::get('/listFollowers', [FollowersController::class, 'listFollowers']);
    Route::get('/listFollowing', [FollowersController::class, 'listFollowing']);
    Route::post('/follow/{id}', [FollowersController::class, 'follow']);
    Route::post('/unfollow/{id}', [FollowersController::class, 'unfollow']);
});

//SEARCH
Route::prefix('search')->group(function(){
    Route::get('/user', [UserController::class, 'searchUsers']);
    Route::get('/post', [PostController::class, 'searchPost']);
});