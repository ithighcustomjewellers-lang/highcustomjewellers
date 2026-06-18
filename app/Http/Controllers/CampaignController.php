<?php

namespace App\Http\Controllers;

use App\Models\Sequence;
use App\Models\CampaignLog;
use App\Jobs\SendCampaignJob;
use App\Models\EmailLinkClick;
use App\Models\Lead;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    public function start($id)
    {
        $lead = Lead::findOrFail($id);
        $sequences = Sequence::where('user_id', $lead->user_id)
            ->whereRaw('UPPER(type) = ?', [strtoupper($lead->type)])
            ->orderBy('step')
            ->get();

        foreach ($sequences as $sequence) {
            $alreadyQueued = CampaignLog::where('user_id', $lead->user_id)
                ->where('lead_id', $lead->id)
                ->where('sequence_id', $sequence->id)
                ->exists();

            if ($alreadyQueued) {
                continue;
            }
            $delay = now()->addDays((int) $sequence->gap_days);

            CampaignLog::create([
                'user_id' => $lead->user_id,
                'lead_id' => $lead->id,
                'sequence_id' => $sequence->id,
                'tracking_token' => Str::uuid(),
                'status' => 'pending',
                'scheduled_at' => $delay,
            ]);

            SendCampaignJob::dispatch(
                $lead->id,
                $sequence->id,
                $lead->user_id
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
                        'status' => 'Not Interested'
                    ]);
            } elseif ($status == 'interested') {

                // Subscribe again
                $lead->is_unsubscribed = 0;
                $lead->save();
            }
        }
        return view('emails.response', compact('status'));
    }


    public function trackOpen($logId)
    {
        $log = CampaignLog::find($logId);
        if ($log && !$log->seen_at) {
            $log->update([
                'seen_at' => now(),
                'status'  => 'seen'
            ]);
            Log::info('CampaignLog Found', [
                'found' => $log ? true : false,
                'log_id' => $logId,
            ]);
        }

        $pixel = base64_decode(
            'R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw=='
        );

        return response($pixel)
            ->header('Content-Type', 'image/gif')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }


    // Add this new method
    public function trackClick($token)
    {
        $click = EmailLinkClick::where('click_token', $token)->first();

        if (!$click) {
            abort(404, 'Invalid tracking link');
        }

        // Update click record if not already clicked
        if (!$click->clicked_at) {
            $click->update([
                'clicked_at' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'click_count' => 1
            ]);

            // Optional: Update campaign log total clicks
            $click->campaignLog->increment('total_clicks');
        } else {
            // Increment click count for duplicate clicks
            $click->increment('click_count');
        }

        // Redirect to original destination
        return redirect($click->destination_url);
    }
}
