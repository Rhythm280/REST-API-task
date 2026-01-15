<?php

namespace App\Http\Controllers;

use App\Services\ProductServices;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{

    protected $productServices;

    public function __construct(ProductServices $productServices)
    {
        $this->productServices = $productServices;
    }

    public function createProduct(ProductRequest $request)
    {
        $product = $this->productServices->createProduct($request);

        return response()->json([
            'status' => true,
            'message' => 'Product created successfully',
            'data' => $product
        ]);
    }

    public function listAllProducts()
    {
        $products = $this->productServices->listAllProducts();

        if (!$products) {
            return response()->json([
                'status' => true,
                'message' => 'No products available',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Products found',
            'data' => $products
        ]);
    }

    public function listProductById($id)
    {
        $product = $this->productServices->listProductById($id);

        if (!$product) {
            return response()->json([
                'status' => true,
                'message' => 'Product not found',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product found',
            'data' => $product
        ]);
    }

    public function updateProduct($id, UpdateProductRequest $request)
    {
        $product = $this->productServices->updateProduct($id, $request);

        if (!$product) {
            return response()->json([
                'status' => true,
                'message' => 'Product not found',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }

    public function deleteProduct($id)
    {
        $product = $this->productServices->deleteProduct($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'No such product found',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully',
            'data' => $product
        ]);
    }
}
