<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CollectionController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth.refresh', 'auth.role:user')->group(function () {
    Route::get('/user-profile', [UserController::class, 'viewUserProfile']);

    // Categories
    Route::get('/categories', [CategoryController::class, 'viewCategories']);
    Route::get('/categories/{id}', [CategoryController::class, 'viewCategoryById']);

    // collections
    Route::post('/collections', [CollectionController::class, 'createCollection']);
    Route::get('/collections', [CollectionController::class, 'viewCollections']);
    Route::get('/collections/{id}', [CollectionController::class, 'viewCollectionByID']);
    Route::delete('/collections/{id}', [CollectionController::class, 'deleteCollection']);
});

Route::middleware('auth.refresh', 'auth.role:admin')->group(function () {
    // Users
    Route::get('/users', [AdminController::class, 'viewUsers']);

    // Categories
    Route::get('/categories', [CategoryController::class, 'viewCategories']);
    Route::post('/categories', [CategoryController::class, 'createCategory']);
    Route::get('/categories/{id}', [CategoryController::class, 'viewCategoryById']);
    Route::put('/categories/{id}', [CategoryController::class, 'updateCategory']);
    Route::delete('/categories/{id}', [CategoryController::class, 'deleteCategory']);

    // collections
    Route::get('/view-collections', [CollectionController::class, 'viewCollections']);
    Route::get('/view-user-collections/{id}', [CollectionController::class, 'viewUserCollections']);
    Route::delete('/delete-user-collections/{id}', [CollectionController::class, 'deleteUserCollections']);
    Route::delete('/delete-collection/{id}', [CollectionController::class, 'deleteCollection']);
});
