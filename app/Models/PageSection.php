<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class PageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'page_id', 'section_name', 'slug', 'order', 'section_type', 'is_listable', 'related_section_id',
        'relation_type', 'relation'
    ];

    static array $RELATIONSHIP_TYPES = [
        'child' => 'Child',
        'parent' => 'Parent',
    ];

    const ELOQUENT_RELATION_MAP = [
        'child' => [
            'hasMany' => 'has_many',
            'hasOne' => 'has_one',
            'belongsToMany' => 'many_to_many',
            'morphMany' => 'morph_many',
        ],
        'parent' => [
            'belongsTo' => 'belongs_to',
            'morphTo' => 'morph_to',
            'belongsToMany' => 'many_to_many',
        ],
    ];

    // Map: [relation_type => [relation => eloquent_method]]
    const ELOQUENT_METHOD_MAP = [
        // Direction: Current model is the 'parent' (owning the relationship)
        'child' => [
            'has_many' => 'hasMany',
            'has_one' => 'hasOne',
            'many_to_many' => 'belongsToMany',
            'morph_many' => 'morphMany',
        ],
        // Direction: Current model is the 'child' (pointing to the parent)
        'parent' => [
            'belongs_to' => 'belongsTo',
            'morph_to' => 'morphTo',
            'many_to_many' => 'belongsToMany',
        ],
    ];

    protected $casts = [
        'is_listable' => 'boolean',
        'child_page_id' => 'array',
    ];

    /**
     * Defines the dynamic relationship based on the current model's attributes.
     * Dynamic Relationship Logic Implementation
     *
     * @return Relation|null
     */
    public function relatedModule()
    {
        $relationType = $this->relation_type; // e.g., 'child'
        $relation = $this->relation;         // e.g., 'has_many'
        $relatedModel = PageSection::class;         // self-referencing

        // check type and relation
        if (
            !isset(self::ELOQUENT_METHOD_MAP[$relationType]) ||
            !isset(self::ELOQUENT_METHOD_MAP[$relationType][$relation])
        ) {
            return $this->hasMany($relatedModel, 'id', 'id')->where('pages.id', 0);
        }

        return match ($relationType) {
            'child' => $this->hasOne($relatedModel, 'id', 'related_section_id'),
            'parent' => $this->belongsTo($relatedModel, 'related_section_id', 'id'),
            default => $this->hasMany($relatedModel, 'id', 'id')->where('pages.id', 0),
        };
    }

    /**
     * Get Page Relationship Direction
     *
     * @param string $relationType
     * @return array|null
     */
    public static function getRelationMapping(string $relationType): ?array
    {
        return collect(self::ELOQUENT_METHOD_MAP)
            ->map(function ($relations, $direction) use ($relationType) {
                return isset($relations[$relationType])
                    ? ['direction' => $direction, 'method' => $relationType] : null;
            })->filter()->first();
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function fields()
    {
        return $this->hasMany(PageSectionField::class);
    }

    public function fieldList(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->hasMany(PageSectionField::class)->pluck('field_name', 'id'),
        );
    }

    public function items()
    {
        return $this->hasMany(PageSectionItem::class);
    }

    public function getItemsCountAttribute()
    {
        return $this->items()->count();
    }
}
