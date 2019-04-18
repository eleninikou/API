<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\ProjectActivity;

class User extends Authenticatable 
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'google_id',
        'password', 
        'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    // public function getJWTIdentifier() {
    //     return $this->getKey();
    // }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    // public function getJWTCustomClaims() {
    //     return [];
    // }


    
    public function setPasswordAttribute($password)
    {
        if ( !empty($password) ) {
            $this->attributes['password'] = bcrypt($password);
        }
    } 


    public function assignedTickets() {
        return $this->hasMany(Ticket::class);
    }

    public function createdTickets() {
        return $this->hasMany(Ticket::class);
    }

    public function projects() { 
        return $this->hasManyThrough(
            Project::class,  
            ProjectUserRole::class, 
            'project_id',  // FK on PUR
            'id', // FK on Project
            'id',// LK on Users
            'user_id' // LK on PUR
        );
    }

    public function milestones() { 
        return $this->hasManyThrough(
            Milestone::class,  
            ProjectUserRole::class, 
            'project_id',  // FK on PUR
            'project_id', // FK on Milestone
            'id',// LK on Users
            'user_id' // LK on PUR
        );
    }

    public function projectRole() {
        return $this->hasManyThrough(
            Role::class, 
            ProjectUserRole::class,
            'role_id',
            'id',
            'id',
            'user_id' 
        );
    }

    public function activities() {
        return $this->hasMany(ProjectActivity::class);
    }

    public static function boot() {
        parent::boot();
        static::deleting(function($user) { 
             $user->projectRole()->delete();
        });
    }


}
