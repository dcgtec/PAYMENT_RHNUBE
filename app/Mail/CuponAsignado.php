<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CuponAsignado extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $nombres;
    public $porcentaje;
    public $cupon;
    public $link;

    public function __construct($nombres, $cupon, $porcentaje, $link)
    {
        $this->nombres = $nombres;
        $this->cupon = $cupon;
        $this->porcentaje = $porcentaje;
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.cuponAsignado')->subject("Gracias por registrarte");
    }
}
