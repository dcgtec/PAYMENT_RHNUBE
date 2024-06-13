<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailChangeRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $token;
    public $status;
    public $title;
    public $codigoValidacion;


    public function __construct($token, $status, $title, $codigoValidacion)
    {
        $this->token = $token;
        $this->status = $status;
        $this->title = $title;
        $this->codigoValidacion = $codigoValidacion;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.changeEmailRequest')->subject('¡Solicitud de cambio de ' . $this->title . '!');
    }
}
