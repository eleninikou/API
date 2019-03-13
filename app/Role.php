<?php

namespace App;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'role',
    ];
    
    protected $table = 'roles';

    public function user() {
        return $this->belongsToMany(User::class);
    }
}
