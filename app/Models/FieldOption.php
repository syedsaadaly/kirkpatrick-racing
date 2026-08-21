<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldOption extends Model
{
    protected $fillable = ['page_section_field_id', 'option_label', 'option_value', 'order'];
}
