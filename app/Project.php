<?php

namespace App;
use App\User;
use App\Milestone;
use App\ProjectActivity;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'creator_id',
        'client_id',
        
    ];
    
    protected $table = 'projects';

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function userRole() {
        return $this->hasMany(ProjectUserRole::class);
    }

    public function client() {
        return $this->belongsTo(User::class, 'client_id', 'id');
    }

    public function milestones() {
        return $this->hasMany(Milestone::class);
    }

    public function tickets() {
        return $this->hasMany(Milestone::class);
    }

    public function activities() {
        return $this->hasMany(ProjectActivity::class);
    }

    public static function boot() {
        parent::boot();
        static::deleting(function($project) { 
             $project->userRole()->delete();
             $project->milestones()->delete();
             $project->tickets()->delete();
             $project->activities()->delete();
        });
    }
}
