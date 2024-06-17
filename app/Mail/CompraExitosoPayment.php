<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompraExitosoPayment extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $codigoGenerado;
    public $customerName;
    public $fecha;
    public $monto;

    public function __construct($codigoGenerado, $customerName, $fecha, $monto)
    {
        $this->codigoGenerado = $codigoGenerado;
        $this->customerName = $customerName;
        $this->fecha = $fecha;
        $this->monto = $monto;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.compra-exitosa')->subject("Compra Exitosa");
    }
}
