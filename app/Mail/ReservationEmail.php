<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class ReservationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $reservation;

    public function __construct($id)
    {
        $emailId = $id;

        // $this->reservation = Reservation::where('email_id', $emailId)
        // ->with(['place', 'services', 'dates', 'hours', 'email'])
        // ->first();
        $this->reservation = Reservation::with(['place', 'services', 'dates', 'hours', 'email'])
        ->where('reservations.email_id', $emailId)
        ->first();
        $this->reservation->email->update([
            'reservation' => true
        ]);

        $url = URL::to('/emails.reservation-email/' . $emailId);
        return $this->view('emails.reservation-email', ['url' => $url]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')),
            subject: 'Reservación de espacio UBB',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-email',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
