<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoryServices;

class CategoryController extends Controller
{

    protected $categoryServices;

    public function __construct(CategoryServices $categoryServices)
    {
        $this->categoryServices = $categoryServices;
    }

    public function viewCategories()
    {
        $categories = $this->categoryServices->viewCategories();
        if (!$categories) {
            return response()->json([
                'status' => false,
                'message' => 'No categories found',
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Categories fetched successfully',
            'data' => [
                'categories' => $categories
            ]
        ]);
    }

    public function createCategory(CategoryRequest $request)
    {
        $category = $this->categoryServices->createCategory($request->all());
        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not created',
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Category created successfully',
            'data' => [
                'category' => $category
            ]
        ]);
    }

    public function viewCategoryByName(string $name)
    {
        $category = $this->categoryServices->viewCategoryByName($name);
        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found',
            ], 404);
        }

        $category->load('products');

        return response()->json([
            'status' => true,
            'message' => 'Category fetched successfully',
            'data' => [
                'category' => $category
            ]
        ]);
    }

    public function updateCategory(string $name, UpdateCategoryRequest $request)
    {
        $category = $this->categoryServices->updateCategoryByName($name, $request->all());
        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found',
            ], 404);
        }

        $category->update($request->only(['name', 'slug', 'description']));

        return response()->json([
            'status' => true,
            'message' => 'Category updated successfully',
            'data' => [
                'category' => $category
            ]
        ]);
    }

    public function deleteCategory(string $name)
    {
        $category = $this->categoryServices->deleteCategoryByName($name);
        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found',
            ], 404);
        }

        $category->delete();
        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully',
            'data' => [
                'category' => $category
            ]
        ], 200);
    }
}
