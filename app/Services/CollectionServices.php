<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Collections;
use App\Models\Products;
use Illuminate\Support\Facades\Log;


class CollectionServices
{
    public function createCollection($data)
    {
        return Collections::create([
            'name' => $data['name'],
            'status' => 'active',
            'user_id' => JWTAuth::user()->id,
        ]);
    }

    public function viewAllCollections()
    {
        $collection = Collections::all();
        if ($collection->isEmpty()) {
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

    public function viewCollectionById($id)
    {
        return Collections::find($id);
    }

    public function userCollection()
    {
        try {
            return Collections::where('user_id', JWTAuth::user()->id)->get();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function viewUserCollections($id)
    {
        try {
            return Collections::where('user_id', $id)->get();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function setCollectionStatus($id)
    {
        try {
            $collection = Collections::where('id', $id)->first();
            if ($collection->status == 'active') {
                $collection->status = 'inactive';
            }
            $collection->save();
            return $collection;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updateCollectionById($id, $data)
    {
        try {
            $collection = Collections::find($id);
            if (!$collection || $collection->user_id !== JWTAuth::user()->id) {
                return false;
            }
            $collection->update($data);
            return $collection;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function addProductToCollection($data)
    {
        try {
            $collection = Collections::where('name', $data['collection_name'])->first();
            $product = Products::find($data['product_id']);

            if (!$collection || !$product) {
                return false;
            }

            // Check if product is already in collection to avoid duplicates (optional, but good practice)
            if (!(JWTAuth::user()->id !== $collection->user_id) && !$collection->products()->where('product_id', $product->id)->exists()) {
                $collection->products()->attach($product->id);
            }
            return $collection;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function removeProductFromCollection($data)
    {
        try {
            $collection = Collections::where('name', $data['collection_name'])->first();
            $product = Products::find($data['product_id']);

            if (!$collection || !$product) {
                return false;
            }

            if (JWTAuth::user()->id !== $collection->user_id) {
                return false;
            }
            $collection->products()->detach($product->id);
            return $collection;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function deleteCollectionById(int $id)
    {
        try {
            $collection = Collections::findOrFail($id);
            if ($collection->user_id !== JWTAuth::user()->id) {
                return false;
            }
            $collection->delete();
            return $collection;
        } catch (\Exception $e) {
            return false;
        }
    }
}