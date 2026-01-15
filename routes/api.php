<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AssetsController;
use App\Http\Controllers\FilterAndSearchController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth.refresh', 'auth.role:user'])->prefix('user')->group(function () {
    Route::get('/user-profile', [UserController::class, 'viewUserProfile']);
    Route::put('/update-profile', [UserController::class, 'updateUserProfile']);

    // Categories
    Route::get('/view-categories', [CategoryController::class, 'viewCategories']);
    Route::get('/view-category/{name}', [CategoryController::class, 'viewCategoryByName']);

    // collections
    Route::post('/collections', [CollectionController::class, 'createCollection']);
    Route::get('/collections', [CollectionController::class, 'userCollections']);
    Route::get('/collections/{id}', [CollectionController::class, 'viewCollectionById']);
    Route::delete('/collections/{id}', [CollectionController::class, 'deleteCollectionById']);
    Route::put('/collections/{id}', [CollectionController::class, 'updateCollectionById']);

    //products
    Route::get('/products', [ProductController::class, 'listAllProducts']);
    Route::get('/products/{id}', [ProductController::class, 'listProductById']);
    Route::post('/add-product-to-collection', [CollectionController::class, 'addProductToCollection']);
    Route::delete('/remove-product-from-collection', [CollectionController::class, 'removeProductFromCollection']);

    // logout
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth.refresh', 'auth.role:admin'])->prefix('admin')->group(function () {
    // Users
    Route::get('/users', [AdminController::class, 'viewUsers']);

    // Categories
    Route::get('/categories', [CategoryController::class, 'viewCategories']);
    Route::post('/categories', [CategoryController::class, 'createCategory']);
    Route::get('/categories/{name}', [CategoryController::class, 'viewCategoryByName']);
    Route::put('/categories/{name}', [CategoryController::class, 'updateCategoryByName']);
    Route::delete('/categories/{name}', [CategoryController::class, 'deleteCategoryByName']);

    // collections
    Route::get('/view-collections', [CollectionController::class, 'viewCollections']);
    Route::get('/view-user-collections/{id}', [CollectionController::class, 'viewUserCollections']);
    Route::put('/set-collections-status/{id}', [CollectionController::class, 'setCollectionStatus']);
    Route::delete('/delete-collection/{id}', [CollectionController::class, 'deleteCollectionById']);

    //products
    Route::post('/products', [ProductController::class, 'createProduct']);
    Route::get('/products', [ProductController::class, 'listAllProducts']);
    Route::get('/products/{id}', [ProductController::class, 'listProductById']);
    Route::put('/products/{id}', [ProductController::class, 'updateProduct']);
    Route::delete('/products/{id}', [ProductController::class, 'deleteProduct']);

    // logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // assets
    Route::post('/products/{id}/assets', [AssetsController::class, 'addAssets']);
    Route::delete('/assets/{id}', [AssetsController::class, 'deleteAsset']);
});
