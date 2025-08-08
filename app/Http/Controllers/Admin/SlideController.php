<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\DisplaySlideResource;
use App\Http\Resources\SlideResource;
use App\Models\Display;
use App\Models\Slide;
use App\Models\SlideDisplayAsset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SlideController extends Controller
{
    public function edit(Request $request, Slide $slide)
    {
        $displays = Display::with(['slideDisplayAssets' =>
            fn ($query) => $query->where('slide_id', $slide->id)]
        )->orderBy('order')->get();

        return Inertia::render('Admin/Slides/Edit', [
            'displays' => DisplaySlideResource::collection($displays),
            'slide' => $slide,
            'slides' => SlideResource::collection(Slide::orderBy('order')->get()),
        ]);
    }

    public function store()
    {
        Slide::query()->create([
            'order' => Slide::query()->max('order') + 1,
        ]);

        return redirect()->back();
    }

    public function reorder(Request $request)
    {
        $orderedIds = $request->input('items');

        foreach ($orderedIds as $index => $id) {
            Slide::query()->where('id', $id)->update(['order' => $index]);
        }

        return new JsonResponse('Slide created successfully.');
    }

    public function destroy(int $id)
    {
        DB::transaction(function () use ( $id){
            $slide = Slide::findOrFail($id);

            $slide->slideDisplayAssets()->each(function ($asset) {
                $asset->clearMediaCollection('slides');
                $asset->delete();
            });

            $slide->delete();
        });

        return response()->json([
            'message' => 'Slide deleted successfully.',
        ]);
    }
}
