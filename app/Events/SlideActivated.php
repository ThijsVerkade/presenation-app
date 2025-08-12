<?php

namespace App\Events;

use App\Models\Display;
use App\Models\Slide;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SlideActivated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected array $channels = [];
    protected array $mediaPaths = [];

    public function __construct(public readonly Slide $slide)
    {
        $this->prepareBroadcastData();
    }

    protected function prepareBroadcastData(): void
    {
        // Only displays that actually have an asset for THIS slide
        $displays = Display::query()
            ->whereHas('slideDisplayAssets', fn ($q) => $q->where('slide_id', $this->slide->id))
            ->with([
                // Load just the single asset for this slide to avoid extra memory
                'slideDisplayAssets' => fn ($q) => $q->where('slide_id', $this->slide->id)->limit(1),
            ])
            ->get(['id', 'slug']);

        foreach ($displays as $display) {
            $this->channels[] = new Channel('display.' . $display->slug);

            $asset = $display->slideDisplayAssets->first(); // collection -> first model
            if ($asset) {
                // If your asset model uses Spatie Media Library, this is fine:
                $this->mediaPaths[$display->slug] = $asset->getFirstMediaUrl('slides') ?: null;
            } else {
                $this->mediaPaths[$display->slug] = null;
            }
        }
    }

    public function broadcastOn(): array
    {
        return $this->channels;
    }

    public function broadcastWith(): array
    {
        return [
            'media_paths' => $this->mediaPaths,
            'slide_id'    => $this->slide->id,
        ];
    }
}
