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

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function role() {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function project() {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
