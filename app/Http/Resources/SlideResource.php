<?php

namespace App\Http\Resources;

use App\Models\Display;
use App\Models\SlideDisplayAsset;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SlideResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $slideAsset = $this->slideDisplayAssets->sortBy('order')->first();

        $mediaUrl = $slideAsset && $slideAsset->hasMedia('slides')
            ? $slideAsset->getFirstMediaUrl('slides')
            : null;

        return [
            'id' => $this->id,
            'is_active' => $this->is_active,
            'order' => $this->order,
            'first_media' => $slideAsset->display->order <= 1 ? $mediaUrl : null
        ];
    }

}
