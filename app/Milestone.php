<?php

namespace App;
use App\Project;
use App\Ticket;
use App\ProjectUserRole;


use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $fillable = [
        'title',
        'focus',
        'project_id',
        'due_date',
    ];
    
    protected $table = 'milestones';
    protected $touches = ['project'];


    public function project() {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }

    public function user() {
        return $this->hasOneThrough(
            User::class,
            ProjectUserRole::class, 
            'user_id',
            'id',
            'project_id',
            'project_id'
        );
    }

    public static function boot() {
        parent::boot();
        static::deleting(function($milestone) { 
             $milestone->tickets()->delete();
        });
    }
}
