<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DisplaySlideResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $slideAsset = $this->slideDisplayAssets;

        $mediaUrl = $slideAsset && $slideAsset->hasMedia('slides')
            ? $slideAsset->getFirstMediaUrl('slides')
            : null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'width' => $this->width,
            'height' => $this->height,
            'order' => $this->order,
            'asset_id' => $slideAsset->id ?? null,
            'media' => $mediaUrl
        ];
    }
}
