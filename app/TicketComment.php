<?php

namespace App;
use App\Ticket;
use App\CommentAttachment;
use App\User;

use Illuminate\Database\Eloquent\Model;

class TicketComment extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'comment'
    ];

      protected $casts = [
        'array' => 'array', // Will convert to (Array)
    ];
    
    protected $table = 'ticket_comments';
    protected $touches = ['ticket'];

    public function ticket() {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function attachments() {
        return $this->hasMany(CommentAttachment::class, 'id', 'ticket_id');
    }


    public static function boot() {
        parent::boot();
        static::deleting(function($comment) { 
             $comment->attachments()->delete();
        });
    }
}
