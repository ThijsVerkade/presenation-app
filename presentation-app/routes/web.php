<?php

use App\Http\Controllers\Admin\DisplayController;
use App\Http\Controllers\Admin\DisplayReorderController;
use App\Http\Controllers\Admin\PlayOverviewController;
use App\Http\Controllers\Admin\SlideActivationController;
use App\Http\Controllers\Admin\SlideController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
});

Route::get('/client', function () {
    return view('client');
});

Route::get('/d/{display:slug}', [DisplayController::class, 'show'])->name('display.view');
Route::post('/admin/slides', [SlideController::class, 'store'])->name('admin.slides.store');
Route::post('/admin/reorder/slides', [SlideController::class, 'reorder'])->name('admin.slides.reorder');
Route::post('/admin/slides/{slide}/activate', [SlideActivationController::class, 'store'])->name('admin.slides.activate');
Route::get('/admin/slides/{slide}/edit', [SlideController::class, 'edit'])->name('admin.slides.edit');

Route::get('/admin/displays', [DisplayController::class, 'index'])->name('admin.displays');
Route::post('/admin/displays', [DisplayController::class, 'store'])->name('admin.displays.store');
Route::post('/admin/displays/{id}', [DisplayController::class, 'update'])->name('admin.displays.update');
Route::post('/admin/reorder/displays', [DisplayReorderController::class, 'store'])->name('admin.displays.reorder');

Route::get('/admin/play', PlayOverviewController::class)->name('admin.presentations.play');
