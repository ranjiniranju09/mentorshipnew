<?php

namespace App\Mail;

use App\Session;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SessionCreatedMentor extends Mailable
{
    use Queueable, SerializesModels;

    public $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function build()
    {
        return $this->subject('New Session Created')
                    ->view('emails.session_created_mentor', ['session' => $this->session]);
    }
}