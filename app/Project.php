<?php

namespace App;
use App\User;
use App\Milestone;

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

    public function client() {
        return $this->belongsTo(User::class, 'client_id', 'id');
    }

    public function milestones() {
        return $this->hasMany(Milestone::class);
    }

    public function tickets() {
        return $this->hasMany(Milestone::class);
    }

    public function team() {
        return $this->hasManyThrough(
            User::class, 
            ProjectUserRole::class,
            'user_id',
            'id'
        );
    }

}


