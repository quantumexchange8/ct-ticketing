<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subtitle extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function title()
    {
        return $this->belongsTo(Title::class, 'title_id', 'id');
    }

    public function contents()
    {
        return $this->hasMany(Content::class, 'subtitle_id', 'id');
    }


}
