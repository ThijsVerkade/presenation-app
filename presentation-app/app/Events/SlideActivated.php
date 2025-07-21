<?php

namespace App\Events;

use App\Models\Slide;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SlideActivated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Slide $slide;

    public function __construct(Slide $slide)
    {
        $this->slide = $slide->load('assets.display');
    }

    public function broadcastOn(): array
    {
        return $this->slide->assets->map(fn($asset) => new Channel('display.' . $asset->display->slug))->toArray();
    }

    public function broadcastWith(): array
    {
        return [
            'media_path' => $this->slide->assets->pluck('media_path', 'display.slug'),
        ];
    }
}
