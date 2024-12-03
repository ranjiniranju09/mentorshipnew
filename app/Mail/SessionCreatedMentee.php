<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Session;

class SessionCreatedMentee extends Mailable
{
    use Queueable, SerializesModels;

    public $session;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Session $session)
    {
         $this->session = $session;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.session_created_mentee')
                    ->subject('New Session Organized');
    }
}
