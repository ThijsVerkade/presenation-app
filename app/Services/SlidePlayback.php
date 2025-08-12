<?php

namespace App\Services;

use App\Events\SlideActivated;
use App\Jobs\AdvanceSlide;
use App\Models\Slide;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SlidePlayback
{
    /**
     * Start playback: always show the first slide.
     * If that slide has a duration (>0), schedule auto-advance.
     * Otherwise, do nothing (manual navigation).
     */
    public function start(): Slide
    {
        $first = Slide::query()->orderBy('order')->firstOrFail();

        $this->setOnPresentation($first);
        broadcast(new SlideActivated($first->fresh()))->toOthers();

        if ($this->hasDuration($first)) {
            $this->scheduleFrom($first);
        }

        return $first->refresh();
    }

    public function next(): Slide
    {
        $current = $this->current();
        $next = $this->nextOf($current);

        $this->setOnPresentation($next);
        broadcast(new SlideActivated($next->fresh()))->toOthers();

        if ($this->hasDuration($next)) {
            $this->scheduleFrom($next);
        }

        return $next;
    }

    /** Go to previous slide; only schedule if the previous slide has a duration. */
    public function previous(): Slide
    {
        $current = $this->current();
        $prev = $this->prevOf($current);

        $this->setOnPresentation($prev);
        broadcast(new SlideActivated($prev->fresh()))->toOthers();

        if ($this->hasDuration($prev)) {
            $this->scheduleFrom($prev);
        }

        return $prev;
    }

    public function stop(): void
    {
        // Set all slides to not be on presentation
        Slide::where('on_presentation', true)->update(['on_presentation' => false]);

        // (Optional) Clear any queued jobs so nothing auto-advances
        // Only works for database/redis queues; otherwise, you can ignore
        if (config('queue.default') === 'database') {
            DB::table('jobs')->truncate();
        }

        broadcast(new SlideActivated(null))->toOthers();
    }

    /** Explicitly go to a slide; only schedule if it has a duration. */
    public function goToSlide(int $slideId): Slide
    {
        $slide = Slide::findOrFail($slideId);

        $this->setOnPresentation($slide);
        broadcast(new SlideActivated($slide->fresh()))->toOthers();

        if ($this->hasDuration($slide)) {
            $this->scheduleFrom($slide);
        }

        return $slide;
    }

    /** Schedule advancing from the given slide. */
    protected function scheduleFrom(Slide $slide): void
    {
        $delay = (int) ($slide->duration_seconds ?? 0);
        if ($delay < 1) {
            return;
        }

        AdvanceSlide::dispatch($slide->id)->delay(now()->addSeconds($delay));
    }

    protected function current(): Slide
    {
        return Slide::query()->where('on_presentation', true)->first()
            ?: Slide::query()->orderBy('order')->firstOrFail();
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

    protected function hasDuration(Slide $slide): bool
    {
        return isset($slide->duration_seconds) && (int) $slide->duration_seconds > 0;
    }

    protected function setOnPresentation(Slide $slide): void
    {
        DB::transaction(function () use ($slide) {
            Slide::where('on_presentation', true)->update(['on_presentation' => false]);
            $slide->update(['on_presentation' => true]);
        });
    }
}
