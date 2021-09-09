<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentSet extends Mailable
{
    use Queueable, SerializesModels;

    public $clientName;
    public $petName;
    public $date;
    public $fee;
    public $services;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($clientName, $petName, $date, $fee, $services)
    {
        $this->clientName = $clientName;
        $this->petName = $petName;
        $this->date = $date;
        $this->fee = $fee;
        $this->services = $services;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.appointment.set');
    }
}
