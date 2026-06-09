<?php

namespace App\Mail;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\PqrsTicket;
use App\Models\TicketQuote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuoteReadyForOwner extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        public readonly PqrsTicket $ticket,
        public readonly TicketQuote $quote,
    ) {}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('view.name')
            ->subject('Cotización lista para revisión - ' . $this->ticket->ticket_number)
            ->with([
                'ticket' => $this->ticket,
                'quote' => $this->quote,
            ]);
    }
}
