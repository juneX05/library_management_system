<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
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
});
