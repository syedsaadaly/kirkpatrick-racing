<?php

namespace App\Models;

use App\Traits\HasDefaultMediaConversions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FieldData extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasDefaultMediaConversions;

    protected $fillable = ['page_section_field_id', 'value', 'item_id', 'alt_text'];

    public function field()
    {
        return $this->belongsTo(PageSectionField::class, 'page_section_field_id');
    }

    public function item()
    {
        return $this->belongsTo(PageSectionItem::class, 'item_id');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->applyDefaultConversions($media);
    }
}
