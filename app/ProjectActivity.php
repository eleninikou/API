<?php

namespace App;
use App\User;
use App\Project;

use Illuminate\Database\Eloquent\Model;

class ProjectActivity extends Model
{
    protected $fillable = [
        'project_id',        
        'user_id',
        'type'
    ];
    
    protected $table = 'project_activities';


    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function user() {
        return $this->belongsTo(user::class);
    }
}
