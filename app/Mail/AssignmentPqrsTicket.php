<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssignmentPqrsTicket extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $contractor;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ticket, $contractor)
     {
         $this->ticket = $ticket;
         $this->contractor = $contractor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.pqrs.assignment-pqrs-ticket')
                    ->subject('Tu solicitud #' . $this->ticket->ticket_number . ' ha sido asignada a un contratista');
    }
}
