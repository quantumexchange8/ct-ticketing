<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportSubCategory extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function supportCategories()
    {
        return $this->belongsTo(SupportCategory::class, 'category_id', 'id');
    }

    public function subtitles()
    {
        return $this->belongsTo(Subtitle::class, 'content_id', 'id');
    }
}
