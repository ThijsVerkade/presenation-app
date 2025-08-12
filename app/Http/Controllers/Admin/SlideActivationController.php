<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\DisplaySlideResource;
use App\Models\Slide;
use App\Services\SlidePlayback;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SlideActivationController extends Controller
{
    public function __construct(private SlidePlayback $playback) {}

    public function index(Request $request)
    {
        $channelId = (string) ($request->input('channel') ?: 'default');

        $slide = $this->playback->start($channelId);

        $displays = $slide->slideDisplayAssets->map(fn ($asset) => $asset->display)->unique();

        return Inertia::render('Admin/Play/Index', [
            'slide'     => $slide,
            'displays'  => DisplaySlideResource::collection($displays),
            'channel'   => $channelId,
        ]);
    }

    public function next(Request $request)
    {
        $channelId = (string) ($request->input('channel') ?: 'default');
        $slide = $this->playback->next($channelId);
        return response()->json(['ok' => true, 'active_slide_id' => $slide->id]);
    }

    public function previous(Request $request)
    {
        $channelId = (string) ($request->input('channel') ?: 'default');
        $slide = $this->playback->previous($channelId);
        return response()->json(['ok' => true, 'active_slide_id' => $slide->id]);
    }

    public function goTo(Request $request, Slide $slide)
    {
        $channelId = (string) ($request->input('channel') ?: 'default');
        $slide = $this->playback->goToSlide($slide->id, $channelId);
        return response()->json(['ok' => true, 'active_slide_id' => $slide->id]);
    }
}
