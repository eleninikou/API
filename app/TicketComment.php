<?php

namespace App;
use App\Ticket;
use App\User;

use Illuminate\Database\Eloquent\Model;

class TicketComment extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'comment'
    ];
    
    protected $table = 'ticket_comments';
    protected $touches = ['ticket'];

    public function ticket() {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
