<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldRelation extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'section_id',
        'field_id',
        'related_page_id',
        'related_section_id',
        'related_field_id',
        'link_type',
        'relationship_type',
        'is_model'
    ];

    protected $casts = [
        'is_model' => 'boolean',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    public function section()
    {
        return $this->belongsTo(PageSection::class, 'section_id');
    }

    public function field()
    {
        return $this->belongsTo(PageSectionField::class, 'field_id');
    }

    public function relatedPage()
    {
        return $this->belongsTo(Page::class, 'related_page_id');
    }

    public function relatedSection()
    {
        return $this->belongsTo(PageSection::class, 'related_section_id');
    }

    public function relatedField()
    {
        return $this->belongsTo(PageSectionField::class, 'related_field_id');
    }
}
