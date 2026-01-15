<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollectionRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\CollectionServices;
use App\Http\Requests\ProductCollectionRequest;
use App\Http\Requests\UpdateCollectionRequest;

class CollectionController extends Controller
{
    protected $collectionServices;

    public function __construct(CollectionServices $collectionServices)
    {
        $this->collectionServices = $collectionServices;
    }

    public function createCollection(CollectionRequest $request)
    {
        $collection = $this->collectionServices->createCollection($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Collection created successfully',
            'data' => [
                'collection' => $collection
            ]
        ]);
    }

    public function viewCollections()
    {
        $collections = $this->collectionServices->viewAllCollections();
        if (!$collections) {
            return response()->json([
                'status' => false,
                'message' => 'No collection found',
            ], 404);
        }

        $collections->products = $collections->products()->get();
        return response()->json([
            'status' => true,
            'message' => 'Collection fetched successfully',
            'data' => [
                'collections' => $collections
            ]
        ]);
    }

    public function viewCollectionById(int $id)
    {
        $collection = $this->collectionServices->viewCollectionById($id);

        if (!$collection) {
            return response()->json([
                'status' => false,
                'message' => 'Collection not found',
            ], 404);
        }

        if ($collection->user_id !== JWTAuth::user()->id) {
            \Illuminate\Support\Facades\Log::info("Auth Error: Collection User ID: " . $collection->user_id . " vs Logged In User ID: " . JWTAuth::user()->id);
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to view this collection',
            ], 403);
        }

        $collection->load('products');
        return response()->json([
            'status' => true,
            'message' => 'Collection fetched successfully',
            'data' => [
                'collection' => $collection
            ]
        ]);
    }

    public function deleteCollectionById(int $id)
    {
        $collection = $this->collectionServices->deleteCollectionById($id);

        if (!$collection) {
            return response()->json([
                'status' => false,
                'message' => 'Collection not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Collection deleted successfully',
            'data' => [
                'collection' => $collection
            ]
        ]);
    }

    public function userCollections()
    {
        $collections = $this->collectionServices->userCollection();
        if (!$collections) {
            return response()->json([
                'status' => false,
                'message' => 'No collection found',
            ], 404);
        }
        $collections->load('products');
        return response()->json([
            'status' => true,
            'message' => 'Collection fetched successfully',
            'data' => [
                'collections' => $collections
            ]
        ]);
    }

    public function viewUserCollections(int $id)
    {
        $collections = $this->collectionServices->viewUserCollections($id);
        if (!$collections) {
            return response()->json([
                'status' => false,
                'message' => 'No collection found',
            ], 404);
        }
        $collections->load('products');
        return response()->json([
            'status' => true,
            'message' => 'Collection fetched successfully',
            'data' => [
                'collections' => $collections
            ]
        ]);
    }

    public function setCollectionStatus(int $id)
    {
        $collection = $this->collectionServices->setCollectionStatus($id);

        if (!$collection) {
            return response()->json([
                'status' => false,
                'message' => 'Collection not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Collection updated successfully',
            'data' => [
                'collection' => $collection
            ]
        ]);
    }

    public function updateCollectionById(int $id, UpdateCollectionRequest $request)
    {
        $collection = $this->collectionServices->updateCollectionById($id, $request->all());

        if (!$collection) {
            return response()->json([
                'status' => false,
                'message' => 'Collection not found or unauthorized',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Collection updated successfully',
            'data' => [
                'collection' => $collection
            ]
        ]);
    }

    public function addProductToCollection(ProductCollectionRequest $request)
    {
        $collection = $this->collectionServices->addProductToCollection($request->all());
        if (!$collection) {
            return response()->json([
                'status' => false,
                'message' => 'Collection not found',
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Product added to collection successfully',
            'data' => [
                'collection' => $collection
            ]
        ]);
    }

    public function removeProductFromCollection(ProductCollectionRequest $request)
    {
        $collection = $this->collectionServices->removeProductFromCollection($request->all());
        if (!$collection) {
            return response()->json([
                'status' => false,
                'message' => 'Collection not found',
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Product removed from collection successfully',
            'data' => [
                'collection' => $collection
            ]
        ]);
    }
}
