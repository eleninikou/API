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
        'project_name'
    ];

    public function project() {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
