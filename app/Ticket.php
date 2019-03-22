<?php

namespace App;
use App\Project;
use App\TicketState;
use App\TicketType;
use App\TicketAttachment;
use App\TicketComment;
use App\User;
use App\Milestone;


use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type_id',
        'status_id',
        'project_id',
        'priority',
        'due_date',
        'creator_id',
        'assigned_user_id',
        'milestone_id',
    ];
    
    protected $table = 'tickets';
    protected $touches = ['project', 'milestone'];


    public function type() {
        return $this->belongsTo(TicketType::class, 'type_id', 'id');
    }

    public function status() {
        return $this->belongsTo(TicketStatus::class, 'status_id', 'id');
    }

    public function project() {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function assignedUser() {
        return $this->belongsTo(User::class, 'assigned_user_id', 'id');
    }

    public function milestone() {
        return $this->belongsTo(Milestone::class, 'milestone_id', 'id');
    }

    public function attachments() {
        return $this->hasMany(TicketAttachment::class);
    }

    public function comments() {
        return $this->hasMany(TicketComment::class);
    }

    public function userRoles() {
        return $this->belongsTo(ProjectUserRole::class, 'project_id', 'project_id');
    }

    public static function boot() {
        parent::boot();
        static::deleting(function($ticket) { 
             $ticket->comments()->delete();
             $ticket->attachments()->delete();
        });
    }
}
