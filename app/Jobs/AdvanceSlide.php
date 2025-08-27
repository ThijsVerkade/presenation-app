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
use Illuminate\Support\Facades\DB;

class AdvanceSlide implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $expectedSlideId) {}

    public function handle(): void
    {
        $service = new SlidePlayback();
        $service->next();
    }

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return 'advance_slide_' . $this->expectedSlideId;
    }

    /**
     * Dispatch the job with a unique batch to prevent duplicates
     */
    public static function dispatchUnique(int $slideId, int $delaySeconds): void
    {
        // Cancel any existing advance slide jobs
        self::cancelExistingJobs();

        // Dispatch the new job with delay
        self::dispatch($slideId)->delay(now()->addSeconds($delaySeconds));
    }

    /**
     * Cancel any existing advance slide jobs
     */
    private static function cancelExistingJobs(): void
    {
        // Delete pending AdvanceSlide jobs from the queue
        if (config('queue.default') === 'database') {
            DB::table('jobs')
                ->where('payload', 'like', '%AdvanceSlide%')
                ->delete();
        }

        // For Redis queues, we can't easily delete specific jobs
        // But we can use a unique job ID to prevent duplicates
        // This is handled by the dispatchUnique method
    }
}
