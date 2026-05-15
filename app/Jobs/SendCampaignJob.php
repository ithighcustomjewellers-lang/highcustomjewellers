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

    protected $lead;
    protected $sequence;
    protected $userId;

    protected $tries = 3;
    protected $timeout = 120;
    /**
     * Create a new job instance.
     */
    public function __construct($lead, $sequence, $userId)
    {
        $this->lead = $lead;
        $this->sequence = $sequence;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::find($this->userId);

        if (!$user) {

            Log::warning("SendCampaignJob: User {$this->userId} not found.");

            return;
        }

        // =========================================
        // ✅ PREVENT DUPLICATE SEND
        // =========================================
        try {

            CampaignLog::create([
                'user_id'     => $this->userId,
                'lead_id'     => $this->lead->id,
                'sequence_id' => $this->sequence->id,
                'sent_at'     => now(),
                'status'      => 'sent'
            ]);
        } catch (UniqueConstraintViolationException $e) {

            Log::info("Duplicate send prevented", [
                'user_id'     => $this->userId,
                'lead_id'     => $this->lead->id,
                'sequence_id' => $this->sequence->id
            ]);

            return;
        }

        // =========================================
        // ✅ VARIANT LOGIC
        // =========================================
        $variant = $this->getContactVariant(
            $this->lead,
            $this->lead->type
        );

        if (
            $this->sequence->variant &&
            $this->sequence->variant != $variant
        ) {

                CampaignLog::where('user_id', $this->userId)
            ->where('lead_id', $this->lead->id)
            ->where('sequence_id', $this->sequence->id)
            ->delete();

            return;
        }

        // =========================================
        // ✅ REPLACE VARIABLES
        // =========================================
        $variables = [
            '[Name]' => $this->lead->name ?? '',
            '[Company Name]' => $this->lead->company_name ?? '',
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

        try {

            // =========================================
            // ✅ GMAIL API
            // =========================================
            if (
                $user->gmail_token &&
                $user->gmail_refresh_token
            ) {

                // =========================================
                // ✅ RENDER FULL BLADE HTML
                // =========================================
                $html = view('emails.sequence', [

                    'lead'         => $this->lead,
                    'sequence'     => $this->sequence,
                    'finalMessage' => $finalMessage,
                    'subjectLine'  => $subject

                ])->render();

                // =========================================
                // ✅ SEND FULL HTML EMAIL
                // =========================================
                $this->sendViaGmailAPI($user,$this->lead->email,$subject,$html);
            } else {

                // =========================================
                // ✅ SMTP FALLBACK
                // =========================================
                Mail::to($this->lead->email)
                    ->send(new SequenceMail($this->lead, $this->sequence,$finalMessage,$subject));
            }

            Log::info("Campaign sent successfully", [

                'lead_id'     => $this->lead->id,
                'sequence_id' => $this->sequence->id,
                'user_id'     => $this->userId,

            ]);
        } catch (\Exception $e) {

            CampaignLog::where('lead_id', $this->lead->id)
                ->where('sequence_id', $this->sequence->id)
                ->delete();

            Log::error("Failed to send campaign", [

                'lead_id'     => $this->lead->id,
                'sequence_id' => $this->sequence->id,
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
    private function sendViaGmailAPI($user,string $to,string $subject, string $html): void {

        $client = new Google_Client();

        $client->setClientId(
            config('services.google.client_id')
        );

        $client->setClientSecret(
            config('services.google.client_secret')
        );

        // =========================================
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

            $newToken = $client->fetchAccessTokenWithRefreshToken(
                $user->gmail_refresh_token
            );

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
            strtr(
                base64_encode($rawMessage),
                '+/',
                '-_'
            ),
            '='
        );

        $msg = new Google_Service_Gmail_Message();

        $msg->setRaw($encodedMessage);

        // =========================================
        // ✅ SEND EMAIL
        // =========================================
        try {

            $service->users_messages->send('me', $msg);
        } catch (\Exception $e) {

            Log::error("Gmail API send error", [

                'error' => $e->getMessage()

            ]);

            throw $e;
        }
    }

    /**
     * =========================================
     * ✅ VARIANT LOGIC
     * =========================================
     */
    private function getContactVariant(
        $lead,
        string $type
    ): ?string {

       $variants = Sequence::where('user_id', $this->userId)
    ->where('type', $type)
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
