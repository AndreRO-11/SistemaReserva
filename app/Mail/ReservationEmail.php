<?php

namespace App\Mail;

use App\Models\Building;
use App\Models\Campus;
use App\Models\Reservation;
use App\Models\User;
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
    public $user;

    public function __construct($id)
    {
        $this->reservation = Reservation::with(['place', 'services', 'dates', 'hours', 'email', 'client', 'user'])
        ->find($id);
        $building = Building::find($this->reservation->place->building_id);
        $campus = Campus::find($building->campus_id);
        $this->user = $this->reservation->place->user;

        $this->reservation->email->update([
            'reservation' => true
        ]);

        $url = URL::to('/emails.reservation-email/' . $this->reservation->email->id);
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
            bcc: [new Address($this->user->email)],
            replyTo: [
                new Address($this->user->email),
            ],
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
