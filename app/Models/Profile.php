<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'nickname',
        'socials_button',
        'skills',
        'resume',
        'about_me',
        'profile_picture',
    ];

    protected $casts = [
        'socials_button' => 'array',
        'skills' => 'array',
    ];

    protected $appends = [
        'profile_picture_url',
        'url_resume',
    ];

    public function getProfilePictureUrlAttribute(): string
    {
        return url($this->profile_picture ?? '');
    }

    public function getUrlResumeAttribute(): ?string
    {
        return url($this->resume);
    }
}
