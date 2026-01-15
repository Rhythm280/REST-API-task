<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetsRequest;
use App\Services\AssetsServices;

class AssetsController extends Controller
{

    protected AssetsServices $assetsServices;

    public function __construct(AssetsServices $assetsServices)
    {
        $this->assetsServices = $assetsServices;
    }

    public function addAssets($id, AssetsRequest $request)
    {
        $assets = $this->assetsServices->addAssets($id, $request->all());
        if (!$assets) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to add assets',
            ], 500);
        }
        return response()->json([
            'status' => true,
            'message' => 'Assets added successfully',
            'data' => $assets,
        ], 200);
    }

    public function deleteAsset($id)
    {
        $asset = $this->assetsServices->deleteAsset($id);
        if (!$asset) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete asset',
            ], 500);
        }
        return response()->json([
            'status' => true,
            'message' => 'Asset deleted successfully',
            'data' => $asset,
        ], 200);
    }
}
