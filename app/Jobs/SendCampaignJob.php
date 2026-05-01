<?php

namespace App\Jobs;

use App\Models\CampaignLog;
use App\Models\Sequence;
use App\Mail\SequenceMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;


class SendCampaignJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    protected $contact, $sequence;

    public function __construct($contact, $sequence)
    {
        $this->contact = $contact;
        $this->sequence = $sequence;
    }


    public function handle()
    {
        // ❌ duplicate check
        $exists = CampaignLog::where('contact_id', $this->contact->id)
            ->where('sequence_id', $this->sequence->id)
            ->exists();

        if ($exists) {
            return;
        }

        // ✅ direct call (FIXED)
        $variant = $this->getContactVariant($this->contact, $this->contact->type);

        // ❌ skip if variant not match
        if ($this->sequence->variant && $this->sequence->variant != $variant) {
            return;
        }

        // 🔥 variables replace
        $variables = [
            '[Name]' => $this->contact->name ?? '',
            '[Company Name]' => $this->contact->company_name ?? '',
        ];

        $finalMessage = str_replace(
            array_keys($variables),
            array_values($variables),
            $this->sequence->message
        );

        $subject = str_replace(
            array_keys($variables),
            array_values($variables),
            $this->sequence->subject
        );

        // 📧 send mail
        Mail::to($this->contact->email)
            ->send(new SequenceMail(
                $this->contact,
                $this->sequence,
                $finalMessage,
                $subject
            ));

        // 📝 log
        CampaignLog::create([
            'contact_id' => $this->contact->id,
            'sequence_id' => $this->sequence->id,
            'sent_at' => now(),
            'status' => 'sent'
        ]);
    }

    private function getContactVariant($contact, $type)
    {
        $variants = Sequence::where('type', $type)
            ->pluck('variant')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        if (empty($variants)) {
            return null;
        }

        // stable distribution
        $index = crc32($contact->email) % count($variants);

        return $variants[$index];
    }
}
