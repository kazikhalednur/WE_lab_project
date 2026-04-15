<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketBookedMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $qrBase64;

    public function __construct(public Ticket $ticket)
    {
        $this->qrBase64 = base64_encode(
            QrCode::format('png')->size(200)->margin(1)->generate($ticket->unique_code)
        );
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your GateKeeper Ticket — '.$this->ticket->event->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket_booked',
            with: [
                'ticket'   => $this->ticket,
                'qrBase64' => $this->qrBase64,
            ],
        );
    }
}
