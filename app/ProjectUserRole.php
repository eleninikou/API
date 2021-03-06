<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Role;
use App\Project;
use App\Milestone;

class ProjectUserRole extends Model
{
    protected $fillable = [
        'user_id',
        'role_id',
        'project_id',        
    ];
    
    protected $table = 'project_user_roles';
    protected $touches = ['project'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function role() {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function tickets() {
        return $this->hasMany(Ticket::class, 'project_id', 'project_id');
    }

    public function milestones() {
        return $this->hasMany(Milestone::class, 'project_id', 'project_id');
    }

}
