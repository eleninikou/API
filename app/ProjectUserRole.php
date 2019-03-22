<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Role;
use App\Project;

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
        return $this->belongsTo(Role::class);
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function tickets() {
        return $this->hasMany(Ticket::class, 'project_id', 'project_id');
    }

}
