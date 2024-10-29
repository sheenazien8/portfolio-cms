<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class Experience extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'currently_working'];

    protected $appends = ['stay'];

    public function getStayAttribute(): string
    {
        return Arr::join([
            Carbon::parse($this->start_date)->format('Y-m'),
            $this->end_date ? Carbon::parse($this->end_date)->format('Y-m') : 'Now',
        ], '-');
    }
}
