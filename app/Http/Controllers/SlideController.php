<?php

namespace App\Http\Controllers;

use App\Events\SlideChanged;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    public function nextSlide(Request $request)
    {
        $slideUrl = $request->string('url');
        broadcast(new SlideChanged($slideUrl));
        return response()->json(['status' => 'broadcasted']);
    }
}
