<?php

namespace App\Http\Controllers;

use App\Models\Display;
use Inertia\Inertia;

class DisplayController extends Controller
{
    public function show(Display $display): \Inertia\Response
    {
        return Inertia::render('Display/Show', [
            'display' => $display,
        ]);
    }
}
