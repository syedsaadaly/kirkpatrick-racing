<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'is_cms', 'icon', 'meta_title', 'meta_description'];

    public function sections()
    {
        return $this->hasMany(PageSection::class);
    }

    /**
     * Get the list of section names keyed by ID.
     */
    protected function sectionList(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->hasMany(PageSection::class)->pluck('section_name', 'id'),
        );
    }

    public function scopeCms($query)
    {
        return $query->where('is_cms', 0);
    }

    public function scopeModule($query)
    {
        return $query->where('is_cms', 1);
    }
}
