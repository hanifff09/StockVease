<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\KondisiController;

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

// Authentication Route 
Route::middleware(['auth', 'json'])->prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->withoutMiddleware('auth');
    Route::delete('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('reset-pass',[AuthController::class, 'resetPassword'])->withoutMiddleware('auth');
});

Route::prefix('setting')->group(function () {
    Route::get('', [SettingController::class, 'index']);
});

Route::get('/transactions/recent', [StaffController::class, 'getRecentTransactions']);


Route::middleware(['auth', 'verified', 'json'])->group(function () {
    Route::prefix('setting')->middleware('can:setting')->group(function () {
        Route::post('', [SettingController::class, 'update']);
    });

    Route::prefix('master')->group(function () {
        Route::middleware('can:master-user')->group(function () {
            Route::get('users', [UserController::class, 'get']);
            Route::post('users', [UserController::class, 'index']);
            Route::post('users/store', [UserController::class, 'store']);
            Route::apiResource('users', UserController::class)
                ->except(['index', 'store'])->scoped(['user' => 'uuid']);
        });

        Route::middleware('can:master-role')->group(function () {
            Route::get('roles', [RoleController::class, 'get'])->withoutMiddleware('can:master-role');
            Route::post('roles', [RoleController::class, 'index']);
            Route::post('roles/store', [RoleController::class, 'store']);
            Route::apiResource('roles', RoleController::class)
                ->except(['index', 'store']);
        });
    });

    Route::prefix('category')->group(function () {
        Route::get('/get', [CategoryController::class, 'get'])->withoutMiddleware(['auth', 'verified', 'json']);
        Route::post('', [CategoryController::class, 'index']);
        Route::post('show', [CategoryController::class, 'show']);
        Route::get('edit/{uuid}', [CategoryController::class, 'edit']);
        Route::post('update/{uuid}', [CategoryController::class, 'update']);
        Route::post('store', [CategoryController::class, 'store']);
        Route::apiResource('category', CategoryController::class)
            ->except(['index', 'store']);
    });

    Route::prefix('kondisi')->group(function () {
        Route::get('/get', [KondisiController::class, 'get']);
        Route::post('', [KondisiController::class, 'index']);
        Route::post('show', [KondisiController::class, 'show']);
        Route::get('edit/{uuid}', [KondisiController::class, 'edit']);
        Route::post('update/{uuid}', [KondisiController::class, 'update']);
        Route::post('store', [KondisiController::class, 'store']);
        Route::apiResource('kondisi', KondisiController::class)
            ->except(['index', 'store']);
    });

    Route::prefix('item')->group(function () {
        Route::get('/get', [ItemController::class, 'get'])->withoutMiddleware(['auth', 'verified', 'json']);;
        Route::post('', [ItemController::class, 'index']);
        Route::post('show', [ItemController::class, 'show']);
        Route::get('edit/{uuid}', [ItemController::class, 'edit'])->withoutMiddleware(['auth', 'verified', 'json']);
        Route::put('update/{uuid}', [ItemController::class, 'update']);
        Route::post('store', [ItemController::class, 'store']);
        Route::apiResource('item', ItemController::class)
            ->except(['index', 'store']);
    });
});
