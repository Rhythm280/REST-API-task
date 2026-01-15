<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Categories;


class CategoryServices
{
    public function createCategory(array $data) {
        try {
            return Categories::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'],
        ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function viewCategories() {
        try {
            return Categories::all();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function viewCategoryByName(string $name) {
        try {
            return Categories::where('name', $name)->first();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function updateCategoryByName(string $name, array $data) {
        try {
            $category = Categories::where('name', $name)->first();
            $category->update($data);
            return $category;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function deleteCategoryByName(string $name) {
        try {
            $category = Categories::where('name', $name)->first();
            $category->delete();
            return $category;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}