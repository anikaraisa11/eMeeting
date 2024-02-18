<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class contacts1 extends Mailable
{
    use Queueable, SerializesModels;
   
    public $data;
  
    public function __construct($data)
    {
        $this->data = $data;
    }
  
    public function build()
    {
        return $this->subject('Contact US - '. $this->data->subject)
                    ->view('pages.contact');
    }
}
