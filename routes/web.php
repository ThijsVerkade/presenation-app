<?php

use App\Http\Controllers\Admin\DisplayController;
use App\Http\Controllers\Admin\DisplayReorderController;
use App\Http\Controllers\Admin\SlideDisplayAssetController;
use App\Http\Controllers\Admin\SlideActivationController;
use App\Http\Controllers\Admin\SlideController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('/admin/displays');
});

Route::get('/client', function () {
    return view('client');
});

Route::get('/d/{display:slug}', [\App\Http\Controllers\DisplayController::class, 'show'])->name('display.view');
Route::post('/admin/slides', [SlideController::class, 'store'])->name('admin.slides.store');
Route::post('/admin/reorder/slides', [SlideController::class, 'reorder'])->name('admin.slides.reorder');
Route::get('/admin/slides/{slide:id}/activate', [SlideActivationController::class, 'index'])->name('admin.slides.activate');
Route::get('/admin/slides/{slide:id}', [SlideController::class, 'edit'])->name('admin.slides');
Route::delete('/admin/slides/{slide:id}', [SlideController::class, 'destroy'])->name('admin.slides.destroy');

Route::get('/admin/displays', [DisplayController::class, 'index'])->name('admin.displays');
Route::post('/admin/displays', [DisplayController::class, 'store'])->name('admin.displays.store');
Route::post('/admin/displays/{id}', [DisplayController::class, 'update'])->name('admin.displays.update');
Route::post('/admin/reorder/displays', [DisplayReorderController::class, 'store'])->name('admin.displays.reorder');

Route::post('/admin/slide-assets', [SlideDisplayAssetController::class, 'store'])->name('admin.slide-assets.store');
Route::delete('/admin/slide-assets/{id}', [SlideDisplayAssetController::class, 'destroy'])->name('admin.slide-assets.destroy');
