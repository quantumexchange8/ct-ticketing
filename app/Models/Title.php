<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Title extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function subtitles()
    {
        return $this->hasMany(Subtitle::class, 'title_id', 'id');
    }
}
