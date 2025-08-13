<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\DisplaySlideResource;
use App\Models\Display;
use App\Models\Slide;
use App\Services\SlidePlayback;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SlideActivationController extends Controller
{
    public function __construct(private SlidePlayback $playback) {}

    public function index(Request $request)
    {
        $slide = Slide::query()->where('on_presentation', true)->first();
        $displays = Display::orderBy('order')->get();

        return Inertia::render('Admin/Play/Index', [
            'slide'    => $slide, // can be null; front-end should handle that state
            'displays' => DisplaySlideResource::collection($displays),
        ]);
    }

    public function start(Request $request)
    {
        $slide = $this->playback->start();

        return response()->json([
            'ok' => true,
            'active_slide_id' => $slide->id,
        ]);
    }

    public function next(Request $request)
    {
        $slide = $this->playback->next();

        return response()->json([
            'ok' => true,
            'active_slide_id' => $slide->id,
        ]);
    }

    public function previous(Request $request)
    {
        $slide = $this->playback->previous();

        return response()->json([
            'ok' => true,
            'active_slide_id' => $slide->id,
        ]);
    }

    public function stop(Request $request)
    {
        $this->playback->stop();

        return response()->json([
            'ok' => true,
            'active_slide_id' => null, // nothing is live now
        ]);
    }

    public function goTo(Request $request, Slide $slide)
    {
        $slide = $this->playback->goToSlide($slide->id);

        return response()->json([
            'ok' => true,
            'active_slide_id' => $slide->id,
        ]);
    }
}
