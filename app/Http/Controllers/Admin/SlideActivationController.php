<?php

namespace App\Http\Controllers\Admin;

use App\Events\SlideActivated;
use App\Http\Controllers\Controller;
use App\Http\Resources\DisplaySlideResource;
use App\Models\Display;
use App\Models\Slide;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SlideActivationController extends Controller
{
    public function index(Request $request, Slide $slide)
    {
        $displays = $slide->slideDisplayAssets->map(function ($asset) {;
            return $asset->display;
        })->unique();

        Slide::query()
            ->where('is_active', true)
            ->where('id', '!=', $slide->id)
            ->update(['is_active' => false]);

        $slide->update(['is_active' => true]);

        broadcast(new SlideActivated($slide));

        return Inertia::render('Admin/Play/Index', [
            'slide' => $slide,
            'displays' => DisplaySlideResource::collection($displays),
        ]);
    }
}
