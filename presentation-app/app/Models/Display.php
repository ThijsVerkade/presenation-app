<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Display extends Model
{
    protected $fillable = ['name', 'width', 'height', 'order'];

    public function assets(): HasMany
    {
        return $this->hasMany(SlideDisplayAsset::class);
    }
}
