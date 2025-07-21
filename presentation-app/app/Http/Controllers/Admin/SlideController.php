<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use Inertia\Inertia;

class SlideController extends Controller
{
    public function edit(Slide $slide)
    {
        return Inertia::render('Admin/Slides/Edit', [
            'slide' => $slide->load('assets.display')
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

        return redirect()->back();
    }
}
