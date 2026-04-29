<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class SequenceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contact, $sequence, $finalMessage, $subjectLine;

    public function __construct($contact, $sequence, $finalMessage, $subjectLine)
    {
        $this->contact = $contact;
        $this->sequence = $sequence;
        $this->finalMessage = $finalMessage;
        $this->subjectLine = $subjectLine;
    }

    public function build()
    {
        $mail = $this->subject($this->subjectLine)
            ->view('emails.sequence')
            ->with([
                'contact' => $this->contact,
                'sequence' => $this->sequence,
                'finalMessage' => $this->finalMessage
            ]);

        // Attach file if exists (as real email attachment)
        // if ($this->sequence->attachments_image && file_exists(public_path($this->sequence->attachments_image))) {
        //     $mail->attach(public_path($this->sequence->attachments_image), [
        //         'as' => $this->sequence->attachment_name ?? 'attachment',
        //         'mime' => mime_content_type(public_path($this->sequence->attachments_image))
        //     ]);
        // }

         if ($this->sequence->hero_image && file_exists(public_path($this->sequence->hero_image))) {
        $mail->attach(public_path($this->sequence->hero_image), [
            'as' => 'Image',
            'mime' => mime_content_type(public_path($this->sequence->hero_image)),
        ]);
        $mail->with('hero_cid', 'hero');
    }

        return $mail;
    }
}
