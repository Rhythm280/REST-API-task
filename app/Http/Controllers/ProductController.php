<?php

namespace App\Http\Controllers;

use App\Services\ProductServices;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\FilterAndSearchServices;
use Illuminate\Http\Request;
class ProductController extends Controller
{

    protected $productServices;
    protected $filterAndSearchServices;


    public function __construct(ProductServices $productServices, FilterAndSearchServices $filterAndSearchServices)
    {
        $this->productServices = $productServices;
        $this->filterAndSearchServices = $filterAndSearchServices;
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

    public function listAllProducts(Request $request)
    {

        if ($request->has('category')) {
            $products = $this->filterAndSearchServices->filterByCategory($request->category);
        } elseif ($request->has('price_min') && $request->has('price_max')) {
            $products = $this->filterAndSearchServices->filterByPriceRange($request->price_min, $request->price_max);
        } elseif ($request->has('search')) {
            $products = $this->filterAndSearchServices->search($request->search);
        } elseif ($request->has('status')) {
            $products = $this->filterAndSearchServices->status($request->status);
        } else {
            $products = $this->productServices->listAllProducts();
        }

        if (!($products)) {
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

        if (!$product || empty($product)) {
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
        $product = $this->productServices->updateProduct($id, $request->all());

        if (!$product || empty($product)) {
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

        if (!$product || empty($product)) {
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
