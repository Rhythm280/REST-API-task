<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Collections;

class CollectionServices {
    public function createCollection($data) {
        return Collections::create([
            'name' => $data['name'],
            'status' => 'active',
            'user_id' => JWTAuth::user()->id,
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
        return $activeCollections;
    }

    public function viewCollectionByID($id) {
        return Collections::findOrFail($id);
    }

    public function deleteCollection($id) {
        $collection = Collections::findOrFail($id);
        if($collection->user_id !== JWTAuth::user()->id ) {
            return false;
        }
        $collection->delete();
        return $collection;
    }

    public function viewUserCollections($id) {
        return Collections::where('user_id', $id)->get();
    }

    public function setUserCollections($id) {
        $collection = Collections::findOrFail($id);
        if($collection->status == 'active') {
            $collection->status = 'inactive';
        }
        $collection->save();
        return $collection;
    }

    public function updateCollection(int $id, $data) {
        $collection = Collections::findOrFail($id);
        if($collection->user_id !== JWTAuth::user()->user_id) {
            return false;
        }
        $collection->update($data);
        return $collection;
    }
}