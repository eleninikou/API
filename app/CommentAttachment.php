<?php

namespace App;
use App\TicketComment;

use Illuminate\Database\Eloquent\Model;

class CommentAttachment extends Model
{
    protected $fillable = [
        'comment_id',
        'attachment'
    ];

protected $table = 'comment_attachments';

public function comment() {
    return $this->belongsTo(TicketComment::class, 'comment_id', 'id');
    }
}

