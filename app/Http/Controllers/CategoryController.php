<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Categories;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class CategoryController extends Controller
{
    public function viewCategories()
    {
        $categories = Categories::all();
        if ($categories->isEmpty()) {
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
        $category = Categories::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Category created successfully',
            'data' => [
                'category' => $category
            ]
        ]);
    }

    public function viewCategoryById(int $id)
    {
        $category = Categories::find($id);
        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Category fetched successfully',
            'data' => [
                'category' => $category
            ]
        ]);
    }

    public function updateCategory(int $id, UpdateCategoryRequest $request)
    {
        $category = Categories::find($id);
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

    public function deleteCategory(int $id)
    {
        $category = Categories::find($id);
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
