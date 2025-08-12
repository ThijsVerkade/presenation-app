<?php

namespace App\Jobs;

use App\Services\SlidePlayback;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

/**
 * Self-cancelling delayed job. If the token changed meanwhile, it no-ops.
 */
class AdvanceSlide implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public string $channelId, public int $token) {}

    public function handle(): void
    {
        $key = "slides:{$this->channelId}:token";
        $currentToken = (int) Cache::get($key, 0);

        if ($currentToken !== $this->token) {
            return; // cancelled
        }

        $service = App::make(SlidePlayback::class);
        $service->next($this->channelId);
    }
}
