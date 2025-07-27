<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Display extends Model
{
    protected $fillable = ['name', 'slug', 'width', 'height', 'order'];

    public function assets(): HasMany
    {
        return $this->hasMany(SlideDisplayAsset::class);
    }

    public function slideDisplayAssets()
    {
        return $this->hasOne(SlideDisplayAsset::class);
    }
}
