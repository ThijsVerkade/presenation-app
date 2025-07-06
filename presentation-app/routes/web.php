<?php

use App\Http\Controllers\DeviceController;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('client');
});

Route::get('/admin/devices', [DeviceController::class, 'index'])->name('admin.devices');
Route::post('/admin/devices/register', [DeviceController::class, 'register'])->name('admin.devices.register');
Route::post('/admin/devices/send', [DeviceController::class, 'send'])->name('admin.devices.send');

Route::post('/register-device', function (Request $request) {
    $device = Device::query()->updateOrCreate(
        ['device_id' => $request->device_id],
    );
    return response()->json(['device' => $device]);
});
