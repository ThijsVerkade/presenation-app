<?php

namespace App\Http\Controllers;

use App\Http\Resources\DisplaySlideResource;
use App\Models\Display;
use App\Models\Slide;
use Inertia\Inertia;

class DisplayController extends Controller
{
    public function show(string $slug): \Inertia\Response
    {
        $slide = Slide::query()->where('is_active', true)->first();

        if ($slide !== null) {
            $display = Display::with(['slideDisplayAssets' =>
                    fn($query) => $query->where('slide_id', $slide->id)]
            )->where('slug', $slug)->first();
        } else {
            $display = Display::where('slug', $slug)->firstOrFail();
        }

        return Inertia::render('Display/Show', [
            'display' => DisplaySlideResource::make($display),
        ]);
    }
}
