<?php

namespace App\Http\Controllers\Admin;

use App\Events\SlideActivated;
use App\Http\Controllers\Controller;
use App\Models\Slide;

class SlideActivationController extends Controller
{
    public function store(Slide $slide)
    {
        Slide::query()
            ->where('presentation_id', $slide->presentation_id)
            ->update(['is_active' => false]);

        $slide->update(['is_active' => true]);

        broadcast(new SlideActivated($slide));

        return back()->with('success', 'Slide activated');
    }
}
