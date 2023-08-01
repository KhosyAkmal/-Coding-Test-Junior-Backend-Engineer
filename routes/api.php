<?php

use App\Http\Controllers\CustomPageController;
use App\Http\Controllers\NewsCategory;
use App\Http\Controllers\NewsCategoryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);

Route::get('news', [NewsController::class, 'index']);
Route::get('news/{id}', [NewsController::class, 'show']);
Route::post('news/comment/{id}', [NewsController::class, 'storeComment']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('news', [NewsController::class, 'store']);
    Route::put('news', [NewsController::class, 'update']);
    Route::delete('news', [NewsController::class, 'destroy']);

    Route::put('custom-pages', [CustomPageController::class, 'update']);
    Route::resource('custom-pages', CustomPageController::class);

    Route::put('news-category', [NewsCategoryController::class, 'update']);
    Route::resource('news-category', NewsCategoryController::class);
    
});

