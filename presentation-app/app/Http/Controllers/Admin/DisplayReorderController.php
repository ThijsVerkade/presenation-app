<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DisplayRequest;
use App\Models\Display;
use App\Models\Slide;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DisplayReorderController extends Controller
{
    public function store(Request $request)
    {
        $orderedIds = $request->input('items');

        foreach ($orderedIds as $index => $id) {

            $display = Display::find($id);
            if ($display) {
                $display->order = $index + 1;
                $display->save();
            }
        }

        return response()->json(['message' => 'Order updated successfully.']);
    }
}
