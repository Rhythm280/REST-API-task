<?php

namespace App\Services;

use App\Models\Products;
use App\Models\Categories;

class ProductServices
{
    public function createProduct($data)
    {
        $category = Categories::where('name', $data['category_name'])->firstOrFail();
        return Products::create([
            'name' => $data['name'],
            'sku' => $data['sku'],
            'description' => $data['description'],
            'price' => $data['price'],
            'category_id' => $category->id,
        ]);
    }

    public function listAllProducts()
    {
        $products = Products::all();

        if ($products->isEmpty()) {
            return false;
        }
        return $products;
    }

    public function listProductById($id)
    {
        return Products::findOrFail($id);
    }

    public function updateProduct($id, $data)
    {
        $product = Products::findOrFail($id);
        $product->update($data);
        return $product;
    }

    public function deleteProduct($id)
    {
        $product = Products::findOrFail($id);
        $product->delete();
        return $product;
    }

}