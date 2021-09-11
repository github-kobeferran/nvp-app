<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentFollowUp extends Mailable
{
    use Queueable, SerializesModels;
    public $clientName;
    public $petName;
    public $last_appointment_at;
    public $services;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($clientName, $petName, $last_appointment_at, $services)    
    {
        $this->clientName = $clientName;
        $this->petName = $petName;
        $this->last_appointment_at = $last_appointment_at;
        $this->services = $services;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.appointment.followup');
    }
}
