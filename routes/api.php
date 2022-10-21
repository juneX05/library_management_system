<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserBookController;
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

Route::middleware('guest')->post('/login', [AuthController::class, 'login']);
Route::middleware('guest')->post('/register', [AuthController::class, 'register']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', function (Request $request) {
        $user = $request->user();
        return response()->json([
            'status' => true,
            'data' => $user,
            'message' => 'LoggedIn user.'
        ]);
    });

    Route::get('/books', [BookController::class, 'index']);
    Route::get('/book/{id}/view', [BookController::class, 'show']);
    Route::post('/book/save', [BookController::class, 'save']);
    Route::post('/book/update', [BookController::class, 'update']);
    Route::post('/book/remove', [BookController::class, 'remove']);

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/user/{id}/view', [UserController::class, 'show']);
    Route::get('/user/{id}/books/favourites', [UserController::class, 'getUserBooksFavourites']);
    Route::get('/user/{id}/books/likes', [UserController::class, 'getUserBooksLikes']);
    Route::get('/user/{id}/books/comments', [UserController::class, 'getUserBooksComments']);
    Route::post('/user/update', [UserController::class, 'update']);
    Route::post('/user/remove', [UserController::class, 'remove']);

    Route::get('/me/books',[UserBookController::class,'index']);
    Route::post('/me/book/manage-favourites',[UserBookController::class,'manageFavouritesBooks']);
    Route::post('/me/book/manage-likes',[UserBookController::class,'manageLikesBooks']);
    Route::post('/me/book/manage-comments',[UserBookController::class,'manageCommentsBooks']);
});
