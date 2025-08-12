<?php

namespace App\Jobs;

use App\Models\Slide;
use App\Services\SlidePlayback;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class AdvanceSlide implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $expectedSlideId) {}

    public function handle(): void
    {
        $service = new SlidePlayback();
        $service->next();
    }
}
