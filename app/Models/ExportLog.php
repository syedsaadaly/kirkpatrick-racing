<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExportLog extends Model
{
    const CMS_TABLE_DEPENDENCIES = [
        "pages" => ["page_sections", "page_section_fields", "page_section_items", "field_data"],
        'page_sections' => ['page_section_fields', "page_section_items", 'field_data'],
        'page_section_items' => ['field_data'],
    ];

    const EXPORT_TABLE_SEQUENCES = [
        'pages',
        'page_sections',
        'page_section_fields',
        'page_section_items',
        'field_data',
    ];

    const EXPORT_TABLES = [
        'CMS Data (Core Content)' => [
            'pages' => 'CMS Pages',
            'page_sections' => 'Page Sections',
            'page_section_fields' => 'Section Fields',
            'page_section_items' => 'Section Items',
            'field_data' => 'Field Data',
        ],

        'Relations / Pivot Tables' => [
            // Assuming a common pivot table like 'model_has_roles' or specific pivot names
            'pivot_tables' => 'Generic Pivot Tables',
        ],

        'Media Assets' => [
            'media' => 'Media (Spatie Media Library)',
        ],

        'Meta / Optional Data' => [
            'seo_data' => 'SEO Data',
            'slugs' => 'Slugs',
            'translations' => 'Translations',
        ],
    ];

    /**
     * The attributes that are mass assignable.
     * Yeh woh fields hain jinhe hum Model::create() ya Model::update() use karke set kar sakte hain.
     */
    protected $fillable = [
        'user_id',
        'exported_at',
        'selected_tables',
        'media_handling',
        'backup_file_path',
        'is_backup_available',
        'notes',
        'extras',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'selected_tables' => 'array', // Automatic JSON decoding ke liye
        'exported_at' => 'datetime',
        'is_backup_available' => 'boolean',
        'extras' => 'json', // Agar yeh JSON ya text field hai
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
