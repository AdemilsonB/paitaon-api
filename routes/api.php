<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FollowersController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Models\Followers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Routes to login and logout in the user
Route::prefix("auth")->group(function(){
    Route::post("login", [AuthController::class, "login"]);
    Route::post("logout", [AuthController::class, "logout"]);
});

//Routes to insert user and view datas
Route::prefix("users")->group(function () {
    Route::get("", [UserController::class , 'list']);
    Route::get("me", [UserController::class , 'me']);
    Route::post("", [AuthController::class , 'register']);
    Route::put("/{id}", [AuthController::class, 'update']);
    Route::delete("/{id}", [AuthController::class, 'delete']);
});

//Routes to Posts and Coments
Route::prefix("posts")->group(function(){
    Route::get("", [PostController::class , 'list']);
    Route::post("", [PostController::class , 'create']);
    Route::put("/{id}", [PostController::class, 'update']);
    Route::delete("/{id}", [PostController::class, 'delete']);

    Route::post("{post}/addComent", [PostController::class,'addComent']);
    Route::delete("{postId}/deleteComent/{comentId}", [PostController::class,'deleteComent']);
});

//Routes add Likes and Dislikes in Post
Route::prefix("likes")->group(function(){
    Route::post("/{id}/like", [LikeController::class, 'like']);
    Route::post("/{id}/dislike", [LikeController::class, 'dislike']);
    Route::post("/{id}/countLikes", [LikeController::class, 'countLikes']);
    Route::post("/{id}/countDislikes", [LikeController::class, 'countDislikes']);
});

//Routes to Follower
Route::prefix("follower")->group(function(){
    route::get('', [FollowersController::class, 'countFollowers']);
    Route::get('/listFollowers', [FollowersController::class, 'listFollowers']);
    Route::post("/{id}/follow", [FollowersController::class, 'follow']);
    Route::post("/{id}/unfollow", [FollowersController::class, 'unfollow']);
});
