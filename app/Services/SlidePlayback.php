<?php

namespace App\Services;

use App\Events\SlideActivated;
use App\Jobs\AdvanceSlide;
use App\Models\Slide;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SlidePlayback
{
    public function start(?string $channelId = 'default'): ?Slide
    {
        $slide = Slide::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->first();

        if (!$slide) {
            $this->bumpToken($channelId);
            return null;
        }

        $this->scheduleFrom($slide, $channelId);

        return $slide->refresh();
    }

    public function next(?string $channelId = 'default'): Slide
    {
        $current = $this->current();
        $next = $this->nextOf($current);
        $this->activate($next, $channelId);
        return $next;
    }

    public function previous(?string $channelId = 'default'): Slide
    {
        $current = $this->current();
        $prev = $this->prevOf($current);
        $this->activate($prev, $channelId);
        return $prev;
    }

    public function goToSlide(int $slideId, ?string $channelId = 'default'): Slide
    {
        $slide = Slide::findOrFail($slideId);
        $this->activate($slide, $channelId);
        return $slide;
    }

    protected function activate(Slide $slide, string $channelId): void
    {
        broadcast(new SlideActivated($slide->fresh()))->toOthers();

        $this->scheduleFrom($slide, $channelId);
    }

    protected function scheduleFrom(Slide $slide, string $channelId): void
    {
        $token = $this->bumpToken($channelId);

        $delay = max(1, (int) ($slide->duration_seconds ?? 0));
        AdvanceSlide::dispatch($channelId, $token)
            ->delay(now()->addSeconds($delay));
    }

    protected function current(): Slide
    {
        return Slide::query()->where('is_active', true)->firstOrFail();
    }

    protected function nextOf(Slide $current): Slide
    {
        return Slide::query()
            ->where('order', '>', $current->order)
            ->orderBy('order')
            ->first()
            ?: Slide::query()->orderBy('order')->firstOrFail();
    }

    protected function prevOf(Slide $current): Slide
    {
        return Slide::query()
            ->where('order', '<', $current->order)
            ->orderByDesc('order')
            ->first()
            ?: Slide::query()->orderByDesc('order')->firstOrFail();
    }

    protected function bumpToken(string $channelId): int
    {
        $key = $this->tokenKey($channelId);

        if (!Cache::has($key)) {
            // Ensure a numeric value exists for increment on DB cache
            Cache::forever($key, 0);
        }

        return (int) Cache::increment($key);
    }

    protected function tokenKey(string $channelId): string
    {
        return "slides:{$channelId}:token";
    }
}
