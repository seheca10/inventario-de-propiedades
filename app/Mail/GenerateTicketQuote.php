<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenerateTicketQuote extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $quote;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ticket, $quote)
    {
        $this->ticket = $ticket;
        $this->quote = $quote;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.pqrs.quote')
                    ->subject('Has recibido cotización para el Ticket #' . $this->ticket->id)
                    ->with([
                        'ticket' => $this->ticket,
                        'quote' => $this->quote,
                    ]);
    }
}
