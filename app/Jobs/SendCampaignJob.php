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

        Log::info("sequence data", [
            'sequence_data' => $sequence,
        ]);

        $html = view('emails.sequence', [
            'lead'         => $lead,
            'sequence'     => $sequence,
            'campaignLog'  => $campaignLog,
            'finalMessage' => $finalMessage,
            'subjectLine'  => $subject
        ])->render();

        // ✅ PASS $finalMessage as parameter
        $html = $this->processAllLinksWithTracking($html, $campaignLog, $sequence, $lead, $finalMessage);


        Log::info("platforms data", [
            'platforms' => $html,
        ]);

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

        // 4. Add all action_links
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

        // 5. Find any other URLs in the message content
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

            Log::info('📝 Tracking link created (not clicked yet)', [
                'platform' => $item['platform'],
                'click_token' => $click->click_token,
                'click_count' => 0,
                'clicked_at' => 'not clicked yet'
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
            'facebook_messenger' => ['facebook.com', 'fb.com', 'm.me', 'messenger.com'],
            'youtube' => ['youtube.com', 'youtu.be']
        ];

        Log::info("platforms data", [
            'platforms' => $platforms,
        ]);


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

        Log::info("normalized data", [
            'normalized' => $normalized,
        ]);

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
            'website' => 'website',
            'business' => 'website'
        ];

        return $mapping[$normalized] ?? $normalized;
    }

    private function sendViaGmailAPI($user, string $to, string $subject, string $html, $sequence): void
    {
        $client = new Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));

        $client->setAccessToken([
            'access_token'  => $user->gmail_token,
            'refresh_token' => $user->gmail_refresh_token,
        ]);

        if ($client->isAccessTokenExpired()) {
            if (!$user->gmail_refresh_token) {
                throw new \Exception("No refresh token available.");
            }

            $newToken = $client->fetchAccessTokenWithRefreshToken($user->gmail_refresh_token);

            if (isset($newToken['access_token'])) {
                $user->gmail_token = $newToken['access_token'];
                $user->save();
                $client->setAccessToken($newToken);
            } else {
                throw new \Exception("Failed to refresh Gmail token.");
            }
        }

        $service = new Google_Service_Gmail($client);
        $boundary = uniqid(rand(), true);

        $rawMessage = "MIME-Version: 1.0\r\n";
        $rawMessage .= "To: {$to}\r\n";
        $rawMessage .= "Subject: {$subject}\r\n";
        $rawMessage .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n\r\n";

        // HTML BODY
        $rawMessage .= "--{$boundary}\r\n";
        $rawMessage .= "Content-Type: text/html; charset=UTF-8\r\n";
        $rawMessage .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $rawMessage .= chunk_split(base64_encode($html)) . "\r\n";

        // ATTACHMENT
        $filePath = base_path('../public_html/' . $sequence->attachments_image);

        Log::info('Attachment Check', [
            'attachment' => $sequence->attachments_image,
            'file_path'  => $filePath,
            'exists'     => file_exists($filePath),
            'is_file'    => is_file($filePath),
        ]);

        if (!empty($sequence->attachments_image) && file_exists($filePath) && is_file($filePath)) {
            Log::info('Attachment Found', ['path' => $filePath]);

            $fileData = chunk_split(base64_encode(file_get_contents($filePath)));
            $fileName = $sequence->attachment_name ?: basename($filePath);
            $mime = mime_content_type($filePath) ?: 'application/octet-stream';

            $rawMessage .= "--{$boundary}\r\n";
            $rawMessage .= "Content-Type: {$mime}; name=\"{$fileName}\"\r\n";
            $rawMessage .= "Content-Disposition: attachment; filename=\"{$fileName}\"\r\n";
            $rawMessage .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $rawMessage .= $fileData . "\r\n";
        } else {
            Log::warning('Attachment file missing', [
                'attachment' => $sequence->attachments_image,
                'file_path'  => $filePath,
            ]);
        }

        $rawMessage .= "--{$boundary}--";

        $encodedMessage = rtrim(strtr(base64_encode($rawMessage), '+/', '-_'), '=');
        $msg = new Google_Service_Gmail_Message();
        $msg->setRaw($encodedMessage);

        try {
            $service->users_messages->send('me', $msg);
        } catch (\Exception $e) {
            Log::error("Gmail API send error", ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
