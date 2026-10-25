<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//#1 Authentication menggunakan Santum
Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/users', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum','ability:SYSTEM:VIEW_USERS');
});
Route::middleware(['auth:sanctum', 'ability:SYSTEM:VIEW_USERS'])->post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);

#2
Route::group(['middleware' => ['auth:sanctum', 'ability:SYSTEM:VIEW_PRODUCTS']], function () {
//Product Routes
    Route::get('/products', [\App\Http\Controllers\Api\ProductController::class, 'index']);
    Route::get('/products/{id}', [\App\Http\Controllers\Api\ProductController::class, 'show']);
    Route::group(['middleware' => ['auth:sanctum', 'ability:SYSTEM:MANAGE_PRODUCTS']], function () {
        Route::post('/products', [\App\Http\Controllers\Api\ProductController::class, 'store']);
        Route::put('/products/{id}', [\App\Http\Controllers\Api\ProductController::class, 'update']);
        Route::delete('/products/{id}', [\App\Http\Controllers\Api\ProductController::class, 'destroy']);
    });
});

Route::group(['middleware' => ['auth:sanctum', 'ability:SYSTEM:VIEW_CATEGORIES']], function () {
//Category Routes
    Route::get('/categories', [\App\Http\Controllers\Api\CategoryController::class, 'index']);
    Route::get('/categories/{id}', [\App\Http\Controllers\Api\CategoryController::class, 'show']);
    Route::group(['middleware' => ['auth:sanctum', 'ability:SYSTEM:MANAGE_CATEGORIES']], function () {
        Route::post('/categories', [\App\Http\Controllers\Api\CategoryController::class, 'store']);
        Route::put('/categories/{id}', [\App\Http\Controllers\Api\CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [\App\Http\Controllers\Api\CategoryController::class, 'destroy']);
    });
});

//Permission Routes
Route::group(['middleware' => ['auth:sanctum', 'ability:SYSTEM:VIEW_PERMISSIONS']], function () {
    Route::get('/permissions', [\App\Http\Controllers\Api\PermissionController::class, 'index']);
    Route::get('/permissions/{id}', [\App\Http\Controllers\Api\PermissionController::class, 'show']);
    Route::group(['middleware' => ['auth:sanctum', 'ability:SYSTEM:MANAGE_PERMISSIONS']], function () {
        Route::post('/permissions', [\App\Http\Controllers\Api\PermissionController::class, 'store']);
        Route::put('/permissions/{id}', [\App\Http\Controllers\Api\PermissionController::class, 'update']);
        Route::delete('/permissions/{id}', [\App\Http\Controllers\Api\PermissionController::class, 'destroy']);
    });
});

//Role Routes
Route::group(['middleware' => ['auth:sanctum', 'ability:SYSTEM:VIEW_ROLES']], function () {
    Route::get('/roles', [\App\Http\Controllers\Api\RoleController::class, 'index']);
    Route::get('/roles/{id}', [\App\Http\Controllers\Api\RoleController::class, 'show']);
    Route::group(['middleware' => ['auth:sanctum', 'ability:SYSTEM:MANAGE_ROLES']], function () {
        Route::post('/roles', [\App\Http\Controllers\Api\RoleController::class, 'store']);
        Route::put('/roles/{id}', [\App\Http\Controllers\Api\RoleController::class, 'update']);
        Route::delete('/roles/{id}', [\App\Http\Controllers\Api\RoleController::class, 'destroy']);
    });
});
