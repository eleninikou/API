<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    protected $fillable = [
        'type',
    ];
    
    protected $table = 'ticket_types';

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }
}
