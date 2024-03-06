<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function supportSubCategories()
    {
        return $this->hasMany(SupportSubCategory::class, 'category_id', 'id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'category_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'category_id', 'id');
    }

    public function projects()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
