<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SlideDisplayAsset;
use Illuminate\Http\Request;

class SlideDisplayAssetController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'slide_id' => 'required|exists:slides,id',
            'display_id' => 'required|exists:displays,id',
            'media' => 'required|file|mimes:jpg,jpeg,png,mp4,mov,webm,pdf',
        ]);

        $asset = SlideDisplayAsset::create([
            'slide_id' => $validated['slide_id'],
            'display_id' => $validated['display_id'],
        ]);

        $asset->addMediaFromRequest('media')->toMediaCollection('slides');

        return redirect()->back()->with('success', 'Asset uploaded and linked.');
    }

    public function destroy(int $id)
    {
        $asset = SlideDisplayAsset::findOrFail($id);

        $asset->clearMediaCollection('slides');
        $asset->delete();

        return response()->json([
            'message' => 'Asset deleted successfully.',
        ]);
    }
}
