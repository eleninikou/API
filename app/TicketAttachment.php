<?php

namespace App;
use App\Ticket;

use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    protected $fillable = [
        'ticket_id',
        'attachment'
    ];
    
    protected $table = 'ticket_attachments';

    public function ticket() {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }
}
