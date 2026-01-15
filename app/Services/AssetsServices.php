<?php

namespace App\Services;

use App\Models\Assets;
use Illuminate\Support\Facades\Storage;

class AssetsServices
{
    public function addAssets($id, $file)
    {
        if (isset($file['file'])) {
            $file_path = $file['file']->store('assets', 'public');
            $productImage = Assets::create([
                'file_path' => $file_path,
                'product_id' => $id,
                'type' => $file['file']->getClientOriginalExtension(),
            ]);
            return $productImage;
        }
        return null;
    }

    public function deleteAsset($id)
    {
        $file = Assets::findOrFail($id);
        $file_path = $file->file_path;

        if (Storage::disk('public')->exists($file_path)) {
            Storage::disk('public')->delete($file_path);
        }
        $file->delete();
        return $file;
    }
}