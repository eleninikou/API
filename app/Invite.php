<?php

namespace App;
use App\Project;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $fillable = [
        'email', 
        'token',
        'project_id',
        'project_name',
        'project_role'
    ];

    public function project() {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function role() {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
