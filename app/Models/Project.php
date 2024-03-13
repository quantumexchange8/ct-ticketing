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

    public function supportSubCategories()
    {
        return $this->hasMany(SupportSubCategory::class, 'project_id', 'id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'pic_id', 'id');
    }

    public function enhancements()
    {
        return $this->hasMany(Enhancement::class, 'project_id', 'id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'project_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'project_id', 'id');
    }
}
