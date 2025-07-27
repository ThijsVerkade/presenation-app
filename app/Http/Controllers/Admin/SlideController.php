<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\DisplaySlideResource;
use App\Models\Display;
use App\Models\Slide;
use App\Models\SlideDisplayAsset;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SlideController extends Controller
{
    public function edit(Request $request, Slide $slide)
    {
        $displays = Display::with(['slideDisplayAssets' =>
            fn ($query) => $query->where('slide_id', $slide->id)]
        )->get();

        return Inertia::render('Admin/Slides/Edit', [
            'displays' => DisplaySlideResource::collection($displays),
            'slide' => $slide,
            'slides' => Slide::orderBy('order')->get(),
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

        return new JsonResource('Slide created successfully.');
    }
}
