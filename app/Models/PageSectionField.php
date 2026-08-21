<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PageSectionField extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['page_section_id', 'field_name', 'field_type', 'default_value', 'is_multiple', 'is_required', 'is_hidden', 'is_related'];

    protected $casts = [
        'is_multiple' => 'boolean',
        'is_required' => 'boolean',
        'is_hidden' => 'boolean',
        'is_related' => 'boolean',
    ];

    public function section()
    {
        return $this->belongsTo(PageSection::class);
    }

    public function fieldData()
    {
        return $this->hasOne(FieldData::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('page-images')
             ->singleFile()
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    public function getImageUrlAttribute()
    {
        return $this->getFirstMediaUrl('page-images');
    }

    public function options()
    {
        return $this->hasMany(FieldOption::class, 'page_section_field_id')->orderBy('order');
    }

    public function relation()
    {
        return $this->hasOne(FieldRelation::class, 'field_id');
    }
}
