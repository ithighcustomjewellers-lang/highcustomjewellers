<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class SequenceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $lead, $sequence, $finalMessage, $subjectLine;

    public function __construct($lead, $sequence, $finalMessage, $subjectLine)
    {
        $this->lead = $lead;
        $this->sequence = $sequence;
        $this->finalMessage = $finalMessage;
        $this->subjectLine = $subjectLine;
    }

    public function build()
    {
        $mail = $this->subject($this->subjectLine)
            ->view('emails.sequence')
            ->with([
                'lead' => $this->lead,
                'sequence' => $this->sequence,
                'finalMessage' => $this->finalMessage
            ]);

        if ($this->sequence->attachments_image && file_exists(public_path($this->sequence->attachments_image))) {
            $mail->attach(public_path($this->sequence->attachments_image), [
                'as' => $this->sequence->attachment_name ?? 'attachment',
                'mime' => mime_content_type(public_path($this->sequence->attachments_image))
            ]);
        }
        return $mail;
    }
}
