<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\CampaignLog;
use App\Models\Sequence;
use App\Mail\SequenceMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

// ✅ Gmail API
use Google_Client;
use Google_Service_Gmail;
use Google_Service_Gmail_Message;
use Illuminate\Support\Facades\Log;

class SendCampaignJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    protected $contact, $sequence, $userId;

    public function __construct($contact, $sequence, $userId)
    {
        $this->contact = $contact;
        $this->sequence = $sequence;
        $this->userId = $userId;
    }

    public function handle()
    {
        $user = User::find($this->userId);

        if (!$user) return;

        // ❌ duplicate
        $exists = CampaignLog::where('contact_id', $this->contact->id)
            ->where('sequence_id', $this->sequence->id)
            ->exists();

        if ($exists) return;

        // ✅ variant logic
        $variant = $this->getContactVariant($this->contact, $this->contact->type);

        if ($this->sequence->variant && $this->sequence->variant != $variant) {
            return;
        }

        // 🔥 variables
        $variables = [
            '[Name]' => $this->contact->name ?? '',
            '[Company Name]' => $this->contact->company_name ?? '',
        ];

        $finalMessage = str_replace(array_keys($variables), array_values($variables), $this->sequence->message);
        $subject = str_replace(array_keys($variables), array_values($variables), $this->sequence->subject);


        // 🚀 SEND MAIL
        if ($user->gmail_token) {
            $this->sendViaGmailAPI($user, $this->contact->email, $subject, $finalMessage);
        } else {
            // fallback SMTP
            Mail::to($this->contact->email)
                ->send(new SequenceMail($this->contact, $this->sequence, $finalMessage, $subject));
        }

        // 📝 log
        CampaignLog::create([
            'contact_id' => $this->contact->id,
            'sequence_id' => $this->sequence->id,
            'sent_at' => now(),
            'status' => 'sent'
        ]);
    }

    // =========================
    // 🔥 GMAIL API FUNCTION
    // =========================
    private function sendViaGmailAPI($user, $to, $subject, $message)
    {
        $client = new Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));

        $client->setAccessToken([
            'access_token' => $user->gmail_token,
            'refresh_token' => $user->gmail_refresh_token,
        ]);


        // 🔁 refresh token
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($user->gmail_refresh_token);
        }

        $service = new Google_Service_Gmail($client);

        $rawMessage = "From: {$user->email}\r\n";
        $rawMessage .= "To: {$to}\r\n";
        $rawMessage .= "Subject: {$subject}\r\n";
        $rawMessage .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
        $rawMessage .= $message;

        $encodedMessage = rtrim(strtr(base64_encode($rawMessage), '+/', '-_'), '=');

        $msg = new Google_Service_Gmail_Message();
        $msg->setRaw($encodedMessage);

        $service->users_messages->send('me', $msg);
    }

    // =========================
    // ✅ VARIANT LOGIC
    // =========================
    private function getContactVariant($contact, $type)
    {
        $variants = Sequence::where('type', $type)
            ->pluck('variant')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        if (empty($variants)) return null;

        $index = crc32($contact->email) % count($variants);

        return $variants[$index];
    }
}
