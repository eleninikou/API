<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Invitation extends Mailable
{
    use Queueable, SerializesModels;

    public $invitation; 

    public function __construct($invitation)
    {
        $this->invitation = $invitation;
    }


    public function build()
    {
        return $this->from('example@example.com')
                    ->markdown('emails.invite');
    }
}
