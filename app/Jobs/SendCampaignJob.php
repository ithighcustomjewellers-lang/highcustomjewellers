<?php

namespace App\Jobs;

use App\Models\CampaignLog;
use App\Mail\SequenceMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;

// 👇 YE IMPORT KARNA ZARURI HAI
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendCampaignJob implements ShouldQueue
{
    // 👇 YE TRAITS MUST HAI
    use Dispatchable, Queueable, SerializesModels;

    protected $contact, $sequence;

    public function __construct($contact, $sequence)
    {
        // dd($this->sequence->message);
        $this->contact = $contact;
        $this->sequence = $sequence;
    }

    public function handle()
{
    $variables = [
        '[Name]' => $this->contact->name ?? '',
        '[Company Name]' => $this->contact->company_name ?? '',
        // '[Email]' => $this->contact->email,
        // '[Phone]' => $this->contact->phone ?? '',
    ];

    // 🔥 message replace
    $finalMessage = str_replace(
        array_keys($variables),
        array_values($variables),
        $this->sequence->message
    );

    // 🔥 subject replace
    $subject = str_replace(
        array_keys($variables),
        array_values($variables),
        $this->sequence->subject
    );

    Mail::to($this->contact->email)
        ->send(new SequenceMail($this->contact, $this->sequence, $finalMessage, $subject));

    CampaignLog::create([
        'contact_id' => $this->contact->id,
        'sequence_id' => $this->sequence->id,
        'sent_at' => now(),
        'status' => 'sent'
    ]);
}
}
