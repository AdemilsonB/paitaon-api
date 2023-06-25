<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
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

Route::prefix("auth")->group(function(){
    Route::post("login", [AuthController::class, "login"]);
    Route::post("logout", [AuthController::class, "logout"]);
});

Route::prefix("users")->group(function(){
    Route::get("me",[UserController::class , 'me']);
    Route::post("",[AuthController::class , 'register']);
});

Route::prefix("posts")->group(function(){
    Route::get("",[PostController::class , 'list']);
    Route::post("",[PostController::class , 'create']);
    Route::post("{post}/addComent",[PostController::class,'addComent']);
});
