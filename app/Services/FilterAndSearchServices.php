<?php

namespace App\Services;

use App\Models\Categories;
use App\Models\Products;
use Exception;

class FilterAndSearchServices
{
    public function filterByCategory(int $category): bool|array
    {
        $categoryModel = Categories::find($category);
        if (!$categoryModel) {
            return false;
        }
        try {
            $products = Products::where('category_id', $categoryModel->id)->get();
            return $this->isEmpty($products);
        } catch (Exception $e) {
            return false;
        }
    }

    public function filterByPriceRange(int $min, int $max): bool|array
    {
        try {
            $products = Products::where("price", ">=", $min)->where("price", "<=", $max)->get();
            return $this->isEmpty($products);
        } catch (Exception $e) {
            return false;
        }
    }

    public function search(string $search): bool|array
    {
        try {
            $products = Products::where("name", "LIKE", "%{$search}%")->get();
            return $this->isEmpty($products);
        } catch (Exception $e) {
            return false;
        }
    }
    public function status(string $status): bool|array
    {
        try {
            $products = Products::where("status", $status)->get();
            return $this->isEmpty($products);
        } catch (Exception $e) {
            return false;
        }
    }

    public function isEmpty($product): bool|array
    {
        $product = $product->toArray();
        if (empty($product)) {
            return false;
        }
        return $product;
    }
}
