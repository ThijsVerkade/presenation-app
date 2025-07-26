<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SlideDisplayAsset extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['display_id', 'slide_id', 'media_url'];

    public function display(): BelongsTo
    {
        return $this->belongsTo(Display::class);
    }

    public function slide(): BelongsTo
    {
        return $this->belongsTo(Slide::class);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('slides')
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('slides')
            ->nonQueued();
    }
}
