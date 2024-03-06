<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function titles()
    {
        return $this->hasMany(Title::class, 'project_id', 'id');
    }

    public function supportCategories()
    {
        return $this->hasMany(SupportCategory::class, 'project_id', 'id');
    }
}
