<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketStatus extends Model
{
    protected $fillable = [
        'status',
    ];
    
    protected $table = 'ticket_status';

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }
}
