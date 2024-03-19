<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enhancement extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function projects()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function versions()
    {
        return $this->belongsTo(Version::class, 'version_id', 'id');
    }
}
