<?php

namespace App\Mail;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class ParticipantReportMail extends Mailable
{
    use Queueable, SerializesModels;
  // public $pdf; 
  public $fileContent;
  public $fileName;
  public $subject;
  public $message;
    /**
     * Create a new message instance.
     */
    public function __construct($fileContent, $fileName, $subject, $message)
    {
        //$this->pdf = $pdf; 
        $this->fileContent = $fileContent;
        $this->fileName = $fileName;
        $this->subject = $subject;
        $this->message = $message;
        
        
    }

    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject:'Questions and Answers Report',
    //     );
    // }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view:  'emails.report', 
    //     );
    // }

     /**
     * Build the message and attach the PDF.
     */
    public function build()
    {
        return   $this->view('emails.report')
        ->subject($this->subject)
        ->attachData($this->fileContent, $this->fileName, [
            'mime' => 'application/pdf',
        ]);
                    
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    // public function attachments(): array
    // {
        
    //     return [ Attachment::fromData($this->pdf->output(), 'report.pdf')
    //     ->as('report.pdf')
    //     ->withMime('application/pdf'),];
    // }
}
