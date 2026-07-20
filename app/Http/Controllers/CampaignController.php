<?php

namespace App\Http\Controllers;

use App\Models\Sequence;
use App\Models\CampaignLog;
use App\Jobs\SendCampaignJob;
use App\Models\EmailLinkClick;
use App\Models\Lead;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    // public function start($id)
    // {
    //     $lead = Lead::findOrFail($id);
    //      $senderIp = request()->ip();

    //     // =========================
    //     // ✅ USER WISE SEQUENCE
    //     // =========================
    //     $sequences = Sequence::where('user_id', $lead->user_id)
    //         ->whereRaw('UPPER(type) = ?', [strtoupper($lead->type)])
    //         ->orderBy('step')
    //         ->get();

    //     foreach ($sequences as $sequence) {

    //         // =========================
    //         // ❌ DUPLICATE CHECK
    //         // =========================
    //         $alreadyQueued = CampaignLog::where('user_id', $lead->user_id)
    //             ->where('lead_id', $lead->id)
    //             ->where('sequence_id', $sequence->id)
    //             ->exists();

    //         if ($alreadyQueued) {
    //             continue;
    //         }

    //         // =========================
    //         // ✅ SEND DATE
    //         // =========================
    //         $delay = now()->addDays((int) $sequence->gap_days);

    //         // =========================
    //         // ✅ CREATE LOG
    //         // =========================
    //         start($id)::create([
    //             'user_id' => $lead->user_id,
    //             'lead_id' => $lead->id,
    //             'sequence_id' => $sequence->id,
    //             'tracking_token' => Str::uuid(),
    //             // 'scheduled_at' => $delay,
    //             'open_token' => Str::random(80),
    //             'status' => 'pending',
    //             'sender_ip' => $senderIp,
    //         ]);


    //         // =========================
    //         // ✅ DISPATCH JOB
    //         // =========================
    //         SendCampaignJob::dispatch(
    //             $lead->id,
    //             $sequence->id,
    //             $lead->user_id,
    //             $senderIp
    //         )->delay($delay);
    //     }

    //     return true;
    // }

    public function start($id)
    {
        $lead = Lead::findOrFail($id);
        $senderIp = request()->ip();

        $sequences = Sequence::where('user_id', $lead->user_id)
            ->whereRaw('UPPER(type) = ?', [strtoupper($lead->type)])
            ->orderBy('step')
            ->get();

        $grouped = $sequences->groupBy('step');

        foreach ($grouped as $step => $stepSequences) {
            // Check if lead already got ANY sequence for this step
            $alreadySentForStep = CampaignLog::where('user_id', $lead->user_id)
                ->where('lead_id', $lead->id)
                ->whereHas('sequence', function ($query) use ($step) {
                    $query->where('step', $step);
                })
                ->exists();

            if ($alreadySentForStep) {
                continue;
            }

            $selectedSequence = $stepSequences->random();
            $delay = now()->addDays((int) $selectedSequence->gap_days);

            CampaignLog::create([
                'user_id'       => $lead->user_id,
                'lead_id'       => $lead->id,
                'sequence_id'   => $selectedSequence->id,
                'tracking_token'=> Str::uuid(),
                'open_token'    => Str::random(80),
                'status'        => 'pending',
                'sender_ip'     => $senderIp,
                'scheduled_at'  => $delay,
            ]);

            SendCampaignJob::dispatch(
                $lead->id,
                $selectedSequence->id,
                $lead->user_id,
                $senderIp
            )->delay($delay);
        }

        return true;
    }

    public function leadResponse($logId, $status)
    {
        if (!in_array($status, ['interested', 'not_interested'])) {
            abort(404);
        }

        $log = CampaignLog::find($logId);

        if (!$log) {
            abort(404);
        }

        $log->status = $status;
        $log->save();

        $lead = Lead::find($log->lead_id);
        if ($lead) {
            if ($status == 'not_interested') {
                // Unsubscribe
                $lead->is_unsubscribed = 1;
                $lead->save();

                CampaignLog::where('lead_id', $lead->id)
                    ->where('status', 'pending')
                    ->update([
                        // 'status' => 'Not Interested'

                        'status' => 'not_interested'
                    ]);
            } elseif ($status == 'interested') {

                // Subscribe again
                $lead->is_unsubscribed = 0;
                $lead->save();
            }
        }

        return view('emails.response', compact('status'));
    }

    // public function trackOpen($logId)
    // {
    //     $log = CampaignLog::find($logId);
    //     if ($log && !$log->seen_at) {
    //         $log->update([
    //             'seen_at' => now(),
    //             'status'  => 'seen'
    //         ]);
    //         Log::info('CampaignLog Found', [
    //             'found' => $log ? true : false,
    //             'log_id' => $logId,
    //         ]);
    //     }

    //     $pixel = base64_decode(
    //         'R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw=='
    //     );

    //     return response($pixel)
    //         ->header('Content-Type', 'image/gif')
    //         ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
    //         ->header('Pragma', 'no-cache')
    //         ->header('Expires', '0');
    // }


    // // Add this new method
    // public function trackClick($token)
    // {
    //     $click = EmailLinkClick::where('click_token', $token)->first();

    //     if (!$click) {
    //         abort(404, 'Invalid tracking link');
    //     }

    //     // Update click record if not already clicked
    //     if (!$click->clicked_at) {
    //         $click->update([
    //             'clicked_at' => now(),
    //             'ip_address' => request()->ip(),
    //             'user_agent' => request()->userAgent(),
    //             'click_count' => 1
    //         ]);

    //         // Optional: Update campaign log total clicks
    //         $click->campaignLog->increment('total_clicks');
    //     } else {
    //         // Increment click count for duplicate clicks
    //         $click->increment('click_count');
    //     }

    //     // Redirect to original destination
    //     return redirect($click->destination_url);
    // }

    /**
     * Track email opens via tracking pixel
     */

    public function trackOpen(Request $request, $token)
    {
        Log::info('🚀 trackOpen: Request received', ['token' => $token]);

        $log = CampaignLog::where('tracking_token', $token)->first();
        if (!$log) {
            Log::warning('❌ TrackOpen: Invalid token', ['token' => $token]);
            return $this->pixelResponse();
        }

        if ($log->seen_at) {
            return $this->pixelResponse();
        }

        $userAgent = strtolower($request->userAgent() ?? '');
        $ip = $request->ip();

        // Sender IP
        // if ($log->sender_ip && $ip === $log->sender_ip) {
        //     Log::info('✅ TrackOpen: Ignored sender own open', ['log_id' => $log->id]);
        //     return $this->pixelResponse();
        // }

        if ($log->sender_ip && strtolower($ip) === strtolower($log->sender_ip)) {
            Log::info('✅ TrackOpen: Ignored sender own open');
            return $this->pixelResponse();
        }

        // Internal IPs
        if (in_array($ip, ['127.0.0.1', '::1'])) {
            Log::info('✅ TrackOpen: Ignored internal IP', ['log_id' => $log->id]);
            return $this->pixelResponse();
        }

        // Bots (but NOT Google proxy)
        $botKeywords = [
            'bot',
            'crawler',
            'spider',
            'preview',
            'scanner',
            'facebookexternalhit',
            'slackbot',
            'linkedinbot',
            'discordbot',
            'whatsapp',
            'telegrambot'
        ];
        foreach ($botKeywords as $bot) {
            if (str_contains($userAgent, $bot)) {
                Log::info('✅ TrackOpen: Ignored bot', ['log_id' => $log->id, 'bot' => $bot]);
                return $this->pixelResponse();
            }
        }

        // ─── ✅ FIXED PREFETCH GUARD (with abs) ───
        if ($log->sent_at) {
            $secondsDiff = abs(now()->diffInSeconds($log->sent_at));
            if (now()->greaterThan($log->sent_at) && $secondsDiff < 60) {
                Log::info('✅ TrackOpen: Ignored prefetch (early open)', [
                    'log_id' => $log->id,
                    'seconds_after_send' => $secondsDiff,
                ]);
                return $this->pixelResponse();
            }
        }

        // ─── All checks passed – mark seen ───
        $log->update([
            'status'            => 'seen',
            'seen_at'           => now(),
            'opened_at'         => now(),
            'opened_ip'         => $ip,
            'opened_user_agent' => $request->userAgent(),
            'is_human_open'     => true,
        ]);

        Log::info('✅✅✅ TrackOpen: SEEN UPDATED!', ['log_id' => $log->id]);
        return $this->pixelResponse();
    }

    /**
     * Track link clicks
     */
    public function trackClick($token)
    {
        Log::info('🔗 TrackClick: Request received', ['token' => $token]);

        $click = EmailLinkClick::where('click_token', $token)->with('campaignLog')->first();

        if (!$click) {
            Log::warning('❌ TrackClick: Invalid token', ['token' => $token]);
            abort(404, 'Invalid tracking link.');
        }

        Log::info('🔗 TrackClick: Click found', ['click_id' => $click->id]);

        // Increment click count
        if (!$click->clicked_at) {
            $click->update([
                'clicked_at'  => now(),
                'ip_address'  => request()->ip(),
                'user_agent'  => request()->userAgent(),
                'click_count' => 1,
            ]);
            Log::info('🔗 TrackClick: First click recorded');
        } else {
            $click->increment('click_count');
            Log::info('🔗 TrackClick: Click count incremented', ['count' => $click->click_count]);
        }

        // If email wasn't opened yet, mark it as seen via click
        if ($click->campaignLog && !$click->campaignLog->seen_at) {
            $click->campaignLog->update([
                'status'            => 'seen',
                'seen_at'           => now(),
                'opened_at'         => now(),
                'opened_ip'         => request()->ip(),
                'opened_user_agent' => request()->userAgent(),
                'is_human_open'     => true,
            ]);
            Log::info('✅ TrackClick: Marked as seen via click', ['log_id' => $click->campaignLog->id]);
        }

        $click->campaignLog->increment('total_clicks');

        Log::info('✅ TrackClick: Redirecting to destination', [
            'campaign_log_id' => $click->campaign_log_id,
            'destination' => $click->destination_url,
        ]);

        return redirect()->away($click->destination_url);
    }



    /**
     * Send transparent 1x1 GIF pixel with anti-caching headers
     */
    private function pixelResponse()
    {
        $pixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==');
        return response($pixel)
            ->header('Content-Type', 'image/gif')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
