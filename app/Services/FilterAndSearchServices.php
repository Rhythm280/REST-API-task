<?php

namespace App\Services;

use App\Models\Categories;
use App\Models\Products;
use ErrorException;
use Illuminate\Database\QueryException;
use Log;

class FilterAndSearchServices
{
    public function filterByCategory(int $category): bool|object
    {
        $category = Categories::where('id', $category)->first();
        try {
            $products = Products::where('category_id', $category->id)->get();
            return $this->isEmpty($products);
        } catch (QueryException | ErrorException $e) {
            return false;
        }
    }

    public function fileterByPriceRange(int $min, int $max): bool|object
    {
        try {
            $products = Products::where("price", ">=", $min)->where("price", "<=", $max)->get();
            return $this->isEmpty($products);
        } catch (QueryException | ErrorException $e) {
            return false;
        }
    }

    public function search(string $search): bool|object
    {
        try {
            $products = Products::where("name", "LIKE", "%{$search}%")->get();
            return $this->isEmpty($products);
        } catch (QueryException | ErrorException $e) {
            return false;
        }
    }
    public function status(string $status): bool|object
    {
        try {
            $products = Products::where("status", $status)->get();
            return $this->isEmpty($products);
        } catch (QueryException | ErrorException $e) {
            return false;
        }
    }

    public function isEmpty($product)
    {
        $product = $product->toArray();
        if (empty($product)) {
            return false;
        }
        return true;
    }
}
