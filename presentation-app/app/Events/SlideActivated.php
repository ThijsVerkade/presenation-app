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

    protected array $channels;
    protected array $mediaPaths;

    public function __construct(public readonly Slide $slide)
    {
        $this->prepareBroadcastData();
    }

    protected function prepareBroadcastData(): void
    {
        $displays = Display::with([
            'slideDisplayAssets' => fn($query) =>
            $query->where('slide_id', $this->slide->id)
        ])->get();

        $this->channels = [];
        $this->mediaPaths = [];

        foreach ($displays as $display) {
            $this->channels[] = new Channel('display.' . $display->slug);

            $asset = $display->slideDisplayAssets;
            if ($asset) {
                $this->mediaPaths[$display->slug] = $asset->getFirstMediaUrl('slides');
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
        ];
    }
}
