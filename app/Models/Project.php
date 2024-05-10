<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'tags' => 'array',
        'tools' => 'array',
        'gallery' => 'array',
        'meta' => 'array',
    ];

    protected $appends = [
        'header_image_url',
    ];

    public function getHeaderImageUrlAttribute(): string
    {
        return url($this->header_image);
    }
}
