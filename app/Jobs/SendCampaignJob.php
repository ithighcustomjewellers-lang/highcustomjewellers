<?php

namespace App\Jobs;
use App\Models\User;
use App\Models\Lead;
use App\Models\CampaignLog;
use App\Models\Sequence;
use App\Mail\SequenceMail;

use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\UniqueConstraintViolationException;

// Google API
use Google_Client;
use Google_Service_Gmail;
use Google_Service_Gmail_Message;

class SendCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $leadId;
    protected $sequenceId;
    protected $userId;

    public $tries = 3;
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(int $leadId, int $sequenceId, int $userId) {
        $this->leadId = $leadId;
        $this->sequenceId = $sequenceId;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // =========================================
        // ✅ FETCH DATA
        // =========================================
        $lead = Lead::find($this->leadId);
        $sequence = Sequence::find($this->sequenceId);
        $user = User::find($this->userId);
        // =========================================
        // ❌ MISSING DATA
        // =========================================
        if (!$lead || !$sequence || !$user) {
            Log::warning('SendCampaignJob missing data', [
                'lead_id'     => $this->leadId,
                'sequence_id' => $this->sequenceId,
                'user_id'     => $this->userId,
            ]);
            return;
        }

        // =========================================
        // ❌ STOP IF UNSUBSCRIBED
        // =========================================
        if ($lead->is_unsubscribed) {
            Log::info('Lead unsubscribed. Mail skipped.', [
                'lead_id' => $lead->id
            ]);
            return;
        }
        // =========================================
        // ✅ GET PENDING CAMPAIGN LOG
        // =========================================
        $campaignLog = CampaignLog::where('lead_id', $lead->id)
            ->where('sequence_id', $sequence->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        // =========================================
        // ❌ ALREADY PROCESSED
        // =========================================
        if (!$campaignLog) {
            Log::info('Campaign already processed', [
                'lead_id'     => $lead->id,
                'sequence_id' => $sequence->id
            ]);
            return;
        }

        // =========================================
        // ✅ REPLACE VARIABLES
        // =========================================
        $variables = [
            '[Name]' => $lead->name
                ?? $lead->fullname
                ?? '',
            '[Company Name]' => $lead->company_name ?? '',
        ];

        $finalMessage = str_replace(
            array_keys($variables),
            array_values($variables),
            $sequence->message
        );

        $subject = str_replace(
            array_keys($variables),
            array_values($variables),
            $sequence->subject
        );

        try {
            // =========================================
            // ✅ GMAIL API
            // =========================================
            if ( $user->gmail_token && $user->gmail_refresh_token) {
                // =========================================
                // ✅ RENDER EMAIL HTML
                // =========================================
                $html = view('emails.sequence', [
                    'lead'         => $lead,
                    'sequence'     => $sequence,
                    'campaignLog'  => $campaignLog,
                    'finalMessage' => $finalMessage,
                    'subjectLine'  => $subject
                ])->render();
                // =========================================
                // ✅ SEND VIA GMAIL API
                // =========================================
                $this->sendViaGmailAPI($user, $lead->email, $subject, $html);
            } else {
                // =========================================
                // ✅ SMTP FALLBACK
                // =========================================
                Mail::to($lead->email)->send(new SequenceMail($lead,$sequence, $finalMessage, $subject));
            }

            // =========================================
            // ✅ UPDATE LOG SUCCESS
            // =========================================
            $campaignLog->update([
                'status'  => 'sent',
                'sent_at' => now()
            ]);

            Log::info("Campaign sent successfully", [
                'lead_id'     => $lead->id,
                'sequence_id' => $sequence->id,
                'user_id'     => $user->id,
            ]);
        } catch (\Exception $e) {
            // =========================================
            // ❌ UPDATE FAILED STATUS
            // =========================================
            $campaignLog->update([
                'status' => 'failed'
            ]);
            Log::error("Failed to send campaign", [

                'lead_id'     => $lead->id,
                'sequence_id' => $sequence->id,
                'error'       => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * =========================================
     * ✅ SEND VIA GMAIL API
     * =========================================
     */
    private function sendViaGmailAPI($user,string $to,string $subject,string $html): void {
        $client = new Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        // ========================================
        // ✅ ACCESS TOKEN
        // =========================================
        $client->setAccessToken([
            'access_token'  => $user->gmail_token,
            'refresh_token' => $user->gmail_refresh_token,
        ]);

        // =========================================
        // ✅ REFRESH TOKEN
        // =========================================
        if ($client->isAccessTokenExpired()) {
            if (!$user->gmail_refresh_token) {
                throw new \Exception(
                    "No refresh token available."
                );
            }

            $newToken = $client->fetchAccessTokenWithRefreshToken($user->gmail_refresh_token);
            if (isset($newToken['access_token'])) {
                $user->gmail_token = $newToken['access_token'];
                $user->save();
                $client->setAccessToken($newToken);
            } else {
                throw new \Exception(
                    "Failed to refresh Gmail token."
                );
            }
        }

        $service = new Google_Service_Gmail($client);
        // =========================================
        // ✅ MIME BOUNDARY
        // =========================================
        $boundary = uniqid(rand(), true);
        // =========================================
        // ✅ RAW HTML EMAIL
        // =========================================
        $rawMessage = "MIME-Version: 1.0\r\n";
        $rawMessage .= "To: {$to}\r\n";
        $rawMessage .= "Subject: {$subject}\r\n";
        $rawMessage .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n\r\n";
        // =========================================
        // ✅ HTML PART
        // =========================================
        $rawMessage .= "--{$boundary}\r\n";
        $rawMessage .= "Content-Type: text/html; charset=UTF-8\r\n";
        $rawMessage .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $rawMessage .= chunk_split(base64_encode($html)) . "\r\n";
        // =========================================
        // ✅ END BOUNDARY
        // =========================================
        $rawMessage .= "--{$boundary}--";
        // =========================================
        // ✅ ENCODE MESSAGE
        // =========================================
        $encodedMessage = rtrim(
            strtr(base64_encode($rawMessage),'+/','-_'),'='
        );

        $msg = new Google_Service_Gmail_Message();
        $msg->setRaw($encodedMessage);

        // =========================================
        // ✅ SEND EMAIL
        // =========================================
        try {
            $service->users_messages->send('me', $msg);
        } catch (\Exception $e) {
            Log::error("Gmail API send error", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * =========================================
     * ✅ VARIANT LOGIC
     * =========================================
     */
    private function getContactVariant($lead, string $type): ?string {
        $variants = Sequence::where('type', $type)
            ->pluck('variant')
            ->filter()
            ->unique()
            ->values()
            ->toArray();
        if (empty($variants)) {
            return null;
        }
        $index = abs(
            crc32($lead->email)
        ) % count($variants);
        return $variants[$index];
    }
}
