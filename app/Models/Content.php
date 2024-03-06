<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function subtitle()
    {
        return $this->belongsTo(Subtitle::class, 'subtitle_id', 'id');
    }

    public function documentationImages()
    {
        return $this->hasMany(DocumentationImage::class, 'content_id', 'id');
    }

    // public function supportSubCategories()
    // {
    //     return $this->hasMany(SupportSubCategory::class, 'content_id', 'id');
    // }
}
