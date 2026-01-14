<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CollectionRequest;
use App\Models\Collections;
use Tymon\JWTAuth\Facades\JWTAuth;

class CollectionController extends Controller
{
    public function createCollection(CollectionRequest $request) {
        $collection = Collections::create([
            'name' => $request->name,
            'status' => 'active',
            'user_id' => JWTAuth::user()->id,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Collection created successfully',
            'data' => [
                'collection' => $collection
            ]
        ]);
    }

    public function viewCollections() {
        $collection = JWTAuth::user()->collections;
        if($collection->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No collection found',
            ], 404);
        }

        $activeCollections = [];
        foreach ($collection as $item) {
            if ($item->status == 'active') {
                $activeCollections[] = $item;
            }
        }
        return response()->json([
            'status' => true,
            'message' => 'Collection fetched successfully',
            'data' => [
                'collections' => $activeCollections
            ]
        ]);
    }

    public function viewCollectionByID(int $id) {
        $collection = Collections::find($id);
        if(!$collection) {
            return response()->json([
                'status' => false,
                'message' => 'Collection not found',
            ], 404);
        }
        if ($collection->user_id !== JWTAuth::user()->id ) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to view this collection',
            ], 403);
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
        $collection = Collections::find($id);
        if(!$collection) {
            return response()->json([
                'status' => false,
                'message' => 'Collection not found',
            ], 404);
        }
        if ($collection->user_id !== JWTAuth::user()->id) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to delete this collection',
            ], 403);
        }
        $collection->delete();
        return response()->json([
            'status' => true,
            'message' => 'Collection deleted successfully',
            'data' => [
                'collection' => $collection
            ]
        ]);
    }
}
