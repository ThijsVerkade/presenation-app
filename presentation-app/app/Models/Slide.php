<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Slide extends Model
{
    protected $fillable = ['order', 'is_active'];

    public function assets(): HasMany
    {
        return $this->hasMany(SlideDisplayAsset::class);
    }

    public function slideDisplayAssets(): HasMany
    {
        return $this->hasMany(SlideDisplayAsset::class);
    }
}
