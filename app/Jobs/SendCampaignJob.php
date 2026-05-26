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
    public function __construct(int $leadId, int $sequenceId, int $userId)
    {
        $this->leadId = $leadId;
        $this->sequenceId = $sequenceId;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $lead = Lead::find($this->leadId);
        $sequence = Sequence::find($this->sequenceId);
        $user = User::find($this->userId);
        if (!$lead || !$sequence || !$user) {
            Log::warning('SendCampaignJob missing data', [
                'lead_id'     => $this->leadId,
                'sequence_id' => $this->sequenceId,
                'user_id'     => $this->userId,
            ]);
            return;
        }

        if ($lead->is_unsubscribed) {
            Log::info('Lead unsubscribed. Mail skipped.', [
                'lead_id' => $lead->id
            ]);
            return;
        }
        $campaignLog = CampaignLog::where('lead_id', $lead->id)->where('sequence_id', $sequence->id)->where('status', 'pending')->latest()->first();
        if (!$campaignLog) {
            Log::info('Campaign already processed', [
                'lead_id'     => $lead->id,
                'sequence_id' => $sequence->id
            ]);
            return;
        }

        $variables = [
            '[Name]' => $lead->name ?? $lead->fullname ?? '',
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
            if ($user->gmail_token && $user->gmail_refresh_token) {
                $html = view('emails.sequence', [
                    'lead'         => $lead,
                    'sequence'     => $sequence,
                    'campaignLog'  => $campaignLog,
                    'finalMessage' => $finalMessage,
                    'subjectLine'  => $subject
                ])->render();
                $this->sendViaGmailAPI($user, $lead->email, $subject, $html, $sequence);
            } else {
                Mail::to($lead->email)->send(new SequenceMail($lead, $sequence, $finalMessage, $subject, $campaignLog));
            }
            $campaignLog->update([
                'status'  => 'send',
                'sent_at' => now()
            ]);

            Log::info("Campaign sent successfully", [
                'lead_id'     => $lead->id,
                'sequence_id' => $sequence->id,
                'user_id'     => $user->id,
            ]);
        } catch (\Exception $e) {
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
    // private function sendViaGmailAPI($user,string $to,string $subject,string $html): void {
    //     $client = new Google_Client();
    //     $client->setClientId(config('services.google.client_id'));
    //     $client->setClientSecret(config('services.google.client_secret'));
    //     // ========================================
    //     // ✅ ACCESS TOKEN
    //     // =========================================
    //     $client->setAccessToken([
    //         'access_token'  => $user->gmail_token,
    //         'refresh_token' => $user->gmail_refresh_token,
    //     ]);

    //     // =========================================
    //     // ✅ REFRESH TOKEN
    //     // =========================================
    //     if ($client->isAccessTokenExpired()) {
    //         if (!$user->gmail_refresh_token) {
    //             throw new \Exception(
    //                 "No refresh token available."
    //             );
    //         }

    //         $newToken = $client->fetchAccessTokenWithRefreshToken($user->gmail_refresh_token);
    //         if (isset($newToken['access_token'])) {
    //             $user->gmail_token = $newToken['access_token'];
    //             $user->save();
    //             $client->setAccessToken($newToken);
    //         } else {
    //             throw new \Exception(
    //                 "Failed to refresh Gmail token."
    //             );
    //         }
    //     }

    //     $service = new Google_Service_Gmail($client);
    //     // =========================================
    //     // ✅ MIME BOUNDARY
    //     // =========================================
    //     $boundary = uniqid(rand(), true);


    //     $rawMessage = "MIME-Version: 1.0\r\n";
    //     $rawMessage .= "To: {$to}\r\n";
    //     $rawMessage .= "Subject: {$subject}\r\n";
    //     $rawMessage .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n\r\n";


    //     // =========================================
    //     // HTML BODY
    //     // =========================================
    //     $rawMessage .= "--{$boundary}\r\n";
    //     $rawMessage .= "Content-Type: text/html; charset=UTF-8\r\n";
    //     $rawMessage .= "Content-Transfer-Encoding: base64\r\n\r\n";
    //     $rawMessage .= chunk_split(base64_encode($html)) . "\r\n";


    //     // =========================================
    //     // ATTACHMENT
    //     // =========================================
    //     if (
    //         !empty($this->sequence->attachments_image)
    //         &&
    //         file_exists(public_path($this->sequence->attachments_image))
    //     ) {

    //         $filePath = public_path(
    //             $this->sequence->attachments_image
    //         );

    //         $fileData = chunk_split(
    //             base64_encode(file_get_contents($filePath))
    //         );

    //         $fileName = $this->sequence->attachment_name
    //             ?? basename($filePath);

    //         $mime = mime_content_type($filePath);

    //         $rawMessage .= "--{$boundary}\r\n";

    //         $rawMessage .= "Content-Type: {$mime}; name=\"{$fileName}\"\r\n";

    //         $rawMessage .= "Content-Disposition: attachment; filename=\"{$fileName}\"\r\n";

    //         $rawMessage .= "Content-Transfer-Encoding: base64\r\n\r\n";

    //         $rawMessage .= $fileData . "\r\n";
    //     }


    //     // =========================================
    //     // END BOUNDARY
    //     // =========================================
    //     $rawMessage .= "--{$boundary}--";
    //     // =========================================
    //     // ✅ ENCODE MESSAGE
    //     // =========================================
    //     $encodedMessage = rtrim(
    //         strtr(base64_encode($rawMessage),'+/','-_'),'='
    //     );

    //     $msg = new Google_Service_Gmail_Message();
    //     $msg->setRaw($encodedMessage);

    //     // =========================================
    //     // ✅ SEND EMAIL
    //     // =========================================
    //     try {
    //         $service->users_messages->send('me', $msg);
    //     } catch (\Exception $e) {
    //         Log::error("Gmail API send error", ['error' => $e->getMessage()]);
    //         throw $e;
    //     }
    // }

    private function sendViaGmailAPI($user, string $to, string $subject, string $html, $sequence): void {

        // =========================================
        // ✅ GOOGLE CLIENT
        // =========================================
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

        // =========================================
        // ✅ GMAIL SERVICE
        // =========================================
        $service = new Google_Service_Gmail($client);

        // =========================================
        // ✅ MIME BOUNDARY
        // =========================================
        $boundary = uniqid(rand(), true);

        // =========================================
        // ✅ EMAIL HEADER
        // =========================================
        $rawMessage = "MIME-Version: 1.0\r\n";

        $rawMessage .= "To: {$to}\r\n";

        $rawMessage .= "Subject: {$subject}\r\n";

        $rawMessage .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n\r\n";

        // =========================================
        // ✅ HTML BODY
        // =========================================
        $rawMessage .= "--{$boundary}\r\n";

        $rawMessage .= "Content-Type: text/html; charset=UTF-8\r\n";

        $rawMessage .= "Content-Transfer-Encoding: base64\r\n\r\n";

        $rawMessage .= chunk_split(
            base64_encode($html)
        ) . "\r\n";

        // =========================================
        // ✅ ATTACHMENT
        // =========================================
        if (
            !empty($sequence->attachments_image)
            &&
            file_exists(
                public_path($sequence->attachments_image)
            )
        ) {

            $filePath = public_path(
                $sequence->attachments_image
            );

            Log::info('Attachment Found', [
                'path' => $filePath
            ]);

            $fileData = chunk_split(
                base64_encode(
                    file_get_contents($filePath)
                )
            );

            $fileName = $sequence->attachment_name
                ?? basename($filePath);

            $mime = mime_content_type($filePath);

            $rawMessage .= "--{$boundary}\r\n";

            $rawMessage .= "Content-Type: {$mime}; name=\"{$fileName}\"\r\n";

            $rawMessage .= "Content-Disposition: attachment; filename=\"{$fileName}\"\r\n";

            $rawMessage .= "Content-Transfer-Encoding: base64\r\n\r\n";

            $rawMessage .= $fileData . "\r\n";
        } else {

            Log::warning('Attachment file missing', [
                'attachment' => $sequence->attachments_image
            ]);
        }

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

        // =========================================
        // ✅ CREATE MESSAGE
        // =========================================
        $msg = new Google_Service_Gmail_Message();

        $msg->setRaw($encodedMessage);

        // =========================================
        // ✅ SEND EMAIL
        // =========================================
        try {

            $service->users_messages->send(
                'me',
                $msg
            );
        } catch (\Exception $e) {

            Log::error(
                "Gmail API send error",
                [
                    'error' => $e->getMessage()
                ]
            );

            throw $e;
        }
    }
}
