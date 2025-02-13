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
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DataBaruController;

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

    Route::prefix('peminjaman')->group(function () {
        Route::get('/get', [PeminjamanController::class, 'get']);
        Route::post('store-admin', [PeminjamanController::class, 'addmin']);
        Route::post('update-admin/{uuid}', [PeminjamanController::class, 'apdet']);
        Route::post('', [PeminjamanController::class, 'index']);
        Route::post('show', [PeminjamanController::class, 'show']);
        Route::get('edit/{uuid}', [PeminjamanController::class, 'edit']);
        Route::post('update/{uuid}', [PeminjamanController::class, 'update']);
        Route::get('monthly-stats', [PeminjamanController::class, 'getMonthlyStats']);
        Route::post('send-otp', [PeminjamanController::class, 'sendOTP'])->withoutMiddleware(['auth', 'verified', 'json']);
        Route::post('verify-otp', [PeminjamanController::class, 'verifyOTP'])->withoutMiddleware(['auth', 'verified', 'json']);
        Route::post('store', [PeminjamanController::class, 'add'])->withoutMiddleware(['auth', 'verified', 'json']);
        Route::apiResource('peminjaman', PeminjamanController::class)
            ->except(['index', 'add']);
    });

    Route::prefix('item')->group(function () {
        Route::get('/get', [ItemController::class, 'get'])->withoutMiddleware(['auth', 'verified', 'json']);;
        Route::post('', [ItemController::class, 'index']);
        Route::post('show', [ItemController::class, 'show']);
        Route::get('edit/{uuid}', [ItemController::class, 'edit'])->withoutMiddleware(['auth', 'verified', 'json']);
        Route::put('update/{uuid}', [ItemController::class, 'update']);
        Route::post('store', [ItemController::class, 'store']);
        Route::put('update-stock/{uuid}', [PeminjamanController::class, 'updateStock'])->withoutMiddleware(['auth', 'verified', 'json']);
        Route::apiResource('item', ItemController::class)
            ->except(['index', 'store']);
    });
    Route::prefix('databaru')->group(function () {
        Route::post('raw', [DataBaruController::class, 'index']);
        Route::get('abc/{uuid}', [DataBaruController::class, 'update']);
        Route::post('confirm', [DataBaruController::class, 'index1']);
        Route::get('def/{uuid}', [DataBaruController::class, 'update1']);  
        Route::post('loan', [DataBaruController::class, 'index2']);
        Route::get('ghi/{uuid}', [DataBaruController::class, 'update2']);  
        Route::post('late', [DataBaruController::class, 'index3']);
        Route::get('mno/{uuid}', [DataBaruController::class, 'update3']);  
        Route::post('done', [DataBaruController::class, 'index4']);
        Route::post('cancel', [DataBaruController::class, 'index5']);
        Route::get('jkl/{uuid}', [DataBaruController::class, 'update5']);  
        Route::get('send-late-email/{uuid}', [DataBaruController::class, 'sendLateReturnEmail']);
        Route::get('send-reject-email/{uuid}', [DataBaruController::class, 'sendRejectEmail']);
        Route::get('send-confirm-email/{uuid}', [DataBaruController::class, 'sendConfirmEmail']);
    });
});
