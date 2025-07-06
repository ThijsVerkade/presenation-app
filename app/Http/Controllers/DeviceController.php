<?php

namespace App\Http\Controllers;

use App\Events\SlideChanged;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::all();

        return view('admin.devices', compact('devices'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'mac' => 'required|string',
            'name' => 'nullable|string'
        ]);

        Device::updateOrCreate(
            ['device_id' => strtolower($request->mac)],
            ['name' => $request->name]
        );

        return redirect()->route('admin.devices')->with('success', 'Device registered!');
    }
    public function send(Request $request)
    {
        $request->validate([
            'mac' => 'required|string',
            'slide' => 'required|string'
        ]);

        Device::updateOrCreate(
            ['device_id' => strtolower($request->mac)],
            [
                'name' => $request->name,
                'slide_url' => $request->slide,
                'updated_at' => now()
            ]
        );

        broadcast(new SlideChanged($request->slide, $request->mac));

        return back()->with('success', 'Slide sent to device!');
    }

}
