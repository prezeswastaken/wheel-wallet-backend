<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\CarPhotoController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
//Public routes
Route::get('hello', function () {
    return response()->json([
        'hello' => 'world!',
        'message' => 'This is a test, and it went flawlessly!'
    ]);
});

//Protected routes
Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::get('user/{id}/cars', [CarController::class, 'read']);
    Route::post('car', [CarController::class, 'store']);
    Route::get('car', [CarController::class, 'index']);
    Route::get('car/{id}', [CarController::class, 'show']);
    Route::put('car/{id}/edit', [CarController::class, 'edit']);
    Route::delete('car/{id}/delete', [CarController::class, 'delete']);
    Route::post('car/{id}/upload', [CarPhotoController::class, 'store']);
    Route::get('car/{id}/photos', [CarPhotoController::class, 'show']);
});
