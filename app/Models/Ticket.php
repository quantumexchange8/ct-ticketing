<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function supportCategories()
    {
        return $this->belongsTo(SupportCategory::class, 'category_id', 'id');
    }

    public function ticketStatus()
    {
        return $this->belongsTo(TicketStatus::class, 'status_id', 'id');
    }

    public function ticketImages()
    {
        return $this->hasMany(TicketImage::class, 'ticket_id', 'id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'pic_id', 'id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'ticket_id', 'id');
    }

    public function projects()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function ticketLogs()
    {
        return $this->hasMany(TicketLog::class, 'ticket_id', 'id');
    }
}
