<?php

namespace App\Traits;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;

trait HasDefaultMediaConversions
{
    public function applyDefaultConversions(Media $media = null): void
    {
        $this->addMediaConversion('icon')
            ->fit(Fit::Crop, 64, 64)
            ->quality(60)
            ->nonQueued();

        $this->addMediaConversion('thumbnail')
            ->fit(Fit::Crop, 300, 300)
            ->quality(70)
            ->nonQueued();

        $this->addMediaConversion('compressed')
            ->quality(80)
            ->nonQueued();
    }
}
