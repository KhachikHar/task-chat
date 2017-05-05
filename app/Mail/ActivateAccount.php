<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivateAccount extends Mailable
{
    use Queueable, SerializesModels;

    protected $token;

    public function __construct($token){
        $this->token = $token;
    }

    public function build(){
        return $this->view('emails.activateAccount', ['token' => $this->token]);
    }
}
