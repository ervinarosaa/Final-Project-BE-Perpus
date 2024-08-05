<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\BorrowController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\RoleController;

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

Route::prefix('v1')->group(function () {
    Route::apiResource("book", BookController::class);
    Route::apiResource("category", CategoryController::class);
    Route::apiResource("role", RoleController::class)->middleware(['auth:api', 'isOwner']);
    Route::get("dashboard", [BookController::class, 'dashboard']);
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login'])->middleware('api');
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
    });
    Route::get('me', [AuthController::class, 'getUser'])->middleware('auth:api');
    Route::post('profile', [ProfileController::class, 'store'])->middleware('auth:api');
    Route::get('profile', [ProfileController::class, 'index'])->middleware('auth:api');
    Route::post('borrow', [BorrowController::class, 'store'])->middleware('auth:api');
    Route::get('borrow', [BorrowController::class, 'index'])->middleware('auth:api');
});
