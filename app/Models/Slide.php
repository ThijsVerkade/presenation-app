<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Slide extends Model
{
    protected $fillable = ['order', 'is_active', 'duration_seconds', 'on_presentation'];

    public function slideDisplayAssets(): HasMany
    {
        return $this->hasMany(SlideDisplayAsset::class);
    }
}
