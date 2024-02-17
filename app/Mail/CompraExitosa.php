<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompraExitosa extends Mailable
{
    use Queueable, SerializesModels;

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

    public function build()
    {
        return $this->view('emails.compra-exitosa');
    }
}
