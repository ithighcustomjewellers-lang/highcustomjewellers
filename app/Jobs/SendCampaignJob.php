<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Lead;
use App\Models\CampaignLog;
use App\Models\Sequence;
use App\Models\EmailLinkClick;  // ✅ ADD THIS
use App\Mail\SequenceMail;

use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;  // ✅ ADD THIS

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
    protected $senderIp;

    /**
     * Create a new job instance.
     */
    public function __construct(int $leadId, int $sequenceId, int $userId, string $senderIp)
    {
        $this->leadId = $leadId;
        $this->sequenceId = $sequenceId;
        $this->userId = $userId;
        $this->senderIp = $senderIp;
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
            return;
        }

        if ($lead->is_unsubscribed) {
            return;
        }

        $campaignLog = CampaignLog::where('lead_id', $lead->id)
            ->where('sequence_id', $sequence->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (!$campaignLog) {
            return;
        }

        if (is_null($campaignLog->sender_ip)) {
            $campaignLog->update(['sender_ip' => $this->senderIp]);
        }

        // Replace variables
        $variables = [
            '[Name]' => $lead->name ?? $lead->fullname ?? '',
            '[Company Name]' => $lead->company_name ?? '',
        ];

        $finalMessage = str_replace(
            array_keys($variables),
            array_values($variables),
            $sequence->message
        );

        // Remove margin from all p tags
        $finalMessage = preg_replace(
            '/<p([^>]*)>/i',
            '<p$1 style="margin:0;padding:0;">',
            $finalMessage
        );

        // Remove extra blank paragraphs
        $finalMessage = preg_replace(
            '/(<p[^>]*>&nbsp;<\/p>\s*){3,}/i',
            '<p>&nbsp;</p><p>&nbsp;</p>',
            $finalMessage
        );

        // Remove new lines
        $finalMessage = preg_replace("/\r|\n/", '', $finalMessage);

        $subject = str_replace(
            array_keys($variables),
            array_values($variables),
            $sequence->subject
        );

        $html = view('emails.sequence', [
            'lead'         => $lead,
            'sequence'     => $sequence,
            'campaignLog'  => $campaignLog,
            'finalMessage' => $finalMessage,
            'subjectLine'  => $subject
        ])->render();

        // ✅ PASS $finalMessage as parameter
        $html = $this->processAllLinksWithTracking($html, $campaignLog, $sequence, $lead, $finalMessage);

        try {
            if ($user->gmail_token && $user->gmail_refresh_token) {
                $this->sendViaGmailAPI($user, $lead->email, $subject, $html, $sequence);
            } else {
                Mail::to($lead->email)->send(new SequenceMail($lead, $sequence, $finalMessage, $subject, $campaignLog));
            }

            $campaignLog->update([
                'status'  => 'send',
                'sent_at' => now()
            ]);
        } catch (\Exception $e) {
            $campaignLog->update([
                'status' => 'failed'
            ]);
            throw $e;
        }
    }

    /**
     * 🔥 MAIN METHOD: Process all links in email and replace with tracking URLs
     */
    protected function processAllLinksWithTracking($html, $campaignLog, $sequence, $lead, $finalMessage)  // ✅ Added $finalMessage
    {
        $urlsToTrack = [];

        // 1. Add WhatsApp link if exists
        if ($sequence->whatsapp_link) {
            $urlsToTrack[] = [
                'url' => $sequence->whatsapp_link,
                'platform' => 'whatsapp'
            ];
        }

        // 2. Add all action_links
        if (!empty($sequence->action_links)) {
            foreach ($sequence->action_links as $link) {
                if (!empty($link['platform_url'])) {
                    $platform = strtolower($link['platform_name'] ?? 'website');
                    $platform = $this->normalizePlatformName($platform);

                    $urlsToTrack[] = [
                        'url' => $link['platform_url'],
                        'platform' => $platform
                    ];
                }
            }
        }

        // 3. Find any other URLs in the message content
        preg_match_all('/https?:\/\/[^\s"\'<>]+/', $finalMessage ?? '', $matches);  // ✅ Now $finalMessage works
        if (!empty($matches[0])) {
            foreach (array_unique($matches[0]) as $url) {
                $exists = false;
                foreach ($urlsToTrack as $existing) {
                    if ($existing['url'] === $url) {
                        $exists = true;
                        break;
                    }
                }
                if (!$exists) {
                    $urlsToTrack[] = [
                        'url' => $url,
                        'platform' => $this->detectPlatformFromUrl($url)
                    ];
                }
            }
        }

        // Create tracking records and replace URLs
        foreach ($urlsToTrack as $item) {
            try {
                // ✅ Jab email send hota hai tab sirf record create hota hai
                // click_count = 0, clicked_at = NULL (kyuki click abhi hua nahi)
                $click = EmailLinkClick::create([
                    'campaign_log_id' => $campaignLog->id,
                    'lead_id' => $lead->id,
                    'user_id' => $this->userId,
                    'sequence_id' => $sequence->id,
                    'platform_name' => $item['platform'],
                    'destination_url' => $item['url'],
                    'click_token' => Str::random(64),
                    'click_count' => 0,        // ✅ Initially 0
                    'clicked_at' => null       // ✅ Initially null (click nahi hua)
                ]);

                // Generate tracking URL
                $trackingUrl = route('track.click', $click->click_token);

                // Replace original URL with tracking URL
                $html = str_replace($item['url'], $trackingUrl, $html);
            } catch (\Exception $e) {
                Log::error('❌ Failed to create tracking link', [
                    'error' => $e->getMessage(),
                    'url' => $item['url']
                ]);
            }
        }

        return $html;
    }

    /**
     * Detect platform from URL
     */
    protected function detectPlatformFromUrl($url)
    {
        $platforms = [
            'whatsapp' => ['wa.me', 'api.whatsapp.com', 'whatsapp.com'],
            'telegram' => ['t.me', 'telegram.me', 'telegram.org'],
            'linkedin' => ['linkedin.com'],
            'instagram' => ['instagram.com'],
            'snapchat' => ['snapchat.com'],
            'x' => ['twitter.com', 'x.com'],
            'threads' => ['threads.net'],
            'facebook_messenger' => ['facebook.com', 'fb.com', 'm.me', 'messenger.com']
        ];

        foreach ($platforms as $platform => $patterns) {
            foreach ($patterns as $pattern) {
                if (strpos($url, $pattern) !== false) {
                    return $platform;
                }
            }
        }

        return 'website';
    }

    /**
     * Normalize platform name
     */
    protected function normalizePlatformName($name)
    {
        $normalized = strtolower(trim($name));
        $mapping = [
            'fb' => 'facebook_messenger',
            'facebook' => 'facebook_messenger',
            'messenger' => 'facebook_messenger',
            'twitter' => 'x',
            'whatsapp' => 'whatsapp',
            'telegram' => 'telegram',
            'linkedin' => 'linkedin',
            'instagram' => 'instagram',
            'snapchat' => 'snapchat',
            'threads' => 'threads',
        ];
        return $mapping[$normalized] ?? $normalized;
    }

    private function sendViaGmailAPI($user, string $to, string $subject, string $html, $sequence): void
    {
        $client = new Google_Client();

        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));

        // Load current token
        $client->setAccessToken([
            'access_token'  => $user->gmail_token,
            'refresh_token' => $user->gmail_refresh_token,
        ]);

        /*
    |--------------------------------------------------------------------------
    | Refresh Token Automatically
    |--------------------------------------------------------------------------
    */

        if ($client->isAccessTokenExpired()) {

            if (!$user->gmail_token_expires_at || now()->greaterThanOrEqualTo($user->gmail_token_expires_at)) {

                if (empty($user->gmail_refresh_token)) {
                    throw new \Exception('No Gmail refresh token found. Please reconnect Gmail.');
                }

                $newToken = $client->fetchAccessTokenWithRefreshToken(
                    $user->gmail_refresh_token
                );

                if (isset($newToken['error'])) {
                    throw new \Exception(
                        $newToken['error_description']
                            ?? 'Unable to refresh Gmail token.'
                    );
                }

                if (!isset($newToken['access_token'])) {
                    throw new \Exception('Failed to refresh Gmail access token.');
                }

                // Save new Access Token
                $user->gmail_token = $newToken['access_token'];

                // Google normally doesn't send refresh token again
                if (!empty($newToken['refresh_token'])) {
                    $user->gmail_refresh_token = $newToken['refresh_token'];
                }

                // Save expiry time
                if (!empty($newToken['expires_in'])) {
                    $user->gmail_token_expires_at = now()->addSeconds(
                        $newToken['expires_in']
                    );
                }

                $user->save();

                // Reload new token into Google Client
                $client->setAccessToken([
                    'access_token'  => $user->gmail_token,
                    'refresh_token' => $user->gmail_refresh_token,
                ]);
            }
        }

        /*
    |--------------------------------------------------------------------------
    | Gmail Service
    |--------------------------------------------------------------------------
    */

        $service = new Google_Service_Gmail($client);

        $boundary = uniqid(rand(), true);

        $rawMessage = "MIME-Version: 1.0\r\n";
        $rawMessage .= "To: {$to}\r\n";
        $rawMessage .= "Subject: {$subject}\r\n";
        $rawMessage .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n\r\n";

        /*
    |--------------------------------------------------------------------------
    | HTML BODY
    |--------------------------------------------------------------------------
    */

        $rawMessage .= "--{$boundary}\r\n";
        $rawMessage .= "Content-Type: text/html; charset=UTF-8\r\n";
        $rawMessage .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $rawMessage .= chunk_split(base64_encode($html)) . "\r\n";

        /*
    |--------------------------------------------------------------------------
    | Attachment
    |--------------------------------------------------------------------------
    */

        if (!empty($sequence->attachments_image)) {

            $filePath = public_path($sequence->attachments_image);

            Log::info('Attachment Check', [
                'attachment' => $sequence->attachments_image,
                'path'       => $filePath,
                'exists'     => file_exists($filePath),
            ]);

            if (file_exists($filePath) && is_file($filePath)) {

                $fileData = chunk_split(base64_encode(file_get_contents($filePath)));

                $fileName = $sequence->attachment_name ?: basename($filePath);

                $mime = mime_content_type($filePath) ?: 'application/octet-stream';

                $rawMessage .= "--{$boundary}\r\n";
                $rawMessage .= "Content-Type: {$mime}; name=\"{$fileName}\"\r\n";
                $rawMessage .= "Content-Disposition: attachment; filename=\"{$fileName}\"\r\n";
                $rawMessage .= "Content-Transfer-Encoding: base64\r\n\r\n";
                $rawMessage .= $fileData . "\r\n";
            } else {

                Log::warning('Attachment missing', [
                    'path' => $filePath
                ]);
            }
        }

        $rawMessage .= "--{$boundary}--";

        /*
    |--------------------------------------------------------------------------
    | Send Mail
    |--------------------------------------------------------------------------
    */

        $encodedMessage = rtrim(
            strtr(base64_encode($rawMessage), '+/', '-_'),
            '='
        );

        $message = new Google_Service_Gmail_Message();
        $message->setRaw($encodedMessage);

        try {

            $service->users_messages->send('me', $message);
        } catch (\Exception $e) {

            Log::error('Gmail API Send Error', [
                'message' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
