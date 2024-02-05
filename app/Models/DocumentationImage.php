<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentationImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function subtitle()
    {
        return $this->belongsTo(Subtitle::class, 'content_id', 'id');
    }

}
