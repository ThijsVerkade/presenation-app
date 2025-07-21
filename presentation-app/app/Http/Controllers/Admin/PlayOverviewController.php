<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Display;
use App\Models\Presentation;
use App\Models\Slide;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlayOverviewController extends Controller
{
    public function __invoke(Slide $slide)
    {
        return Inertia::render('Admin/Play/Index', [
            'slide' => $slide,
        ]);
    }
}
