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

    // public function contents()
    // {
    //     return $this->belongsTo(Content::class, 'content_id', 'id');
    // }

    public function projects()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
