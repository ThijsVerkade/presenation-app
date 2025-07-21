<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DisplayRequest;
use App\Models\Display;
use App\Models\Slide;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DisplayController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Displays/Index', [
            'displays' => Display::orderBy('order')->get(),
            'slides' => Slide::orderBy('order')->get(),
        ]);
    }

    public function store(DisplayRequest $request)
    {
        $validated = $request->validated();

        Display::query()->create([
            'order' => Display::query()->max('order') + 1,
            'name' => $validated['name'],
            'width' => $validated['width'],
            'height' => $validated['height'],
        ]);

        return redirect()->route('admin.displays');
    }

    public function update(DisplayRequest $request, $id)
    {
        $display = Display::query()->findOrFail($id);

        $validated = $request->validated();

        $display->update([
            'order' => $validated['order'] ?? $display->order,
            'name' => $validated['name'],
            'width' => $validated['width'],
            'height' => $validated['height'],
        ]);

        return redirect()->route('admin.displays');
    }
}
