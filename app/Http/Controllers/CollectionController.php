<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CollectionRequest;
use App\Models\Collections;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\CollectionServices;

class CollectionController  extends Controller
{
    protected $collectionServices;

    public function __construct(CollectionServices $collectionServices)
    {
        $this->collectionServices = $collectionServices;
    }

    public function createCollection(CollectionRequest $request) {
        $collection = $this->collectionServices->createCollection($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Collection created successfully',
            'data' => [
                'collection' => $collection
            ]
        ]);
    }

    public function viewCollections() {
        $collections = $this->collectionServices->viewCollections();
        if(!$collections) {
            return response()->json([
                'status' => false,
                'message' => 'No collection found',
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Collection fetched successfully',
            'data' => [
                'collections' => $collections
            ]
        ]);
    }

    public function viewCollectionByID(int $id) {
        $collection = $this->collectionServices->viewCollectionByID($id);

        if ($collection->user_id !== JWTAuth::user()->id ) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to view this collection',
            ], 403);
        }

        if(!$collection) {
            return response()->json([
                'status' => false,
                'message' => 'Collection not found',
            ], 404);
        }
        
        return response()->json([
            'status' => true,
            'message' => 'Collection fetched successfully',
            'data' => [
                'collection' => $collection
            ]
        ]);
    }

    public function deleteCollection(int $id) {
        $collection = $this->collectionServices->viewCollectionByID($id);

        if(!$collection) {
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

    public function viewUserCollections(int $id) {
        $collections = $this->collectionServices->viewCollections($id);
        if(!$collections) {
            return response()->json([
                'status' => false,
                'message' => 'No collection found',
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Collection fetched successfully',
            'data' => [
                'collections' => $collections
            ]
        ]);
    }

    public function setCollectionStatus(int $id) {
        $collection = $this->collectionServices->viewCollectionByID($id);

        if(!$collection) {
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

    public function updateCollection(int $id, UpdateCollectionRequest $request) {
        $collection = $this->collectionServices->viewCollectionByID($id, $request->all());

        if(!$collection) {
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
}
