<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'temp_file_path',
        'uploaded_at',
        'completed_at',
        'overwrite_records',
        'error_message',
        'note',
        'extras',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'completed_at' => 'datetime',
        'overwrite_records' => 'boolean',
        'extras' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function canBeRetried()
    {
        return $this->status === 'FAILED' &&
            \File::isDirectory(storage_path('app/' . $this->temp_file_path));
    }

}
