<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SequenceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;
    public $sequence;
    public $finalMessage;
    public $subjectLine;
    public $campaignLog;

    public function __construct($lead,$sequence,$finalMessage,$subjectLine,$campaignLog) {
        $this->lead = $lead;
        $this->sequence = $sequence;
        $this->finalMessage = $finalMessage;
        $this->subjectLine = $subjectLine;
        $this->campaignLog = $campaignLog;
    }

    public function build()
    {
        $mail = $this->subject($this->subjectLine)
            ->view('emails.sequence')
            ->with([
                'lead' => $this->lead,
                'sequence' => $this->sequence,
                'finalMessage' => $this->finalMessage,
                'subjectLine' => $this->subjectLine,
                'campaignLog' => $this->campaignLog
            ]);

        // =========================================
        // ATTACHMENT
        // =========================================

        if (!empty($this->sequence->attachments_image) && file_exists(public_path($this->sequence->attachments_image)))

            {

            Log::info(public_path($this->sequence->attachments_image));
            $filePath = public_path(
                $this->sequence->attachments_image
            );

            $mail->attach($filePath, [
                'as' => $this->sequence->attachment_name ?? 'attachment',
                'mime' => mime_content_type($filePath)
            ]);
        }

        return $mail;
    }
}
