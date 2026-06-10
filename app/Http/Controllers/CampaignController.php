<?php

namespace App\Http\Controllers;

use App\Models\Sequence;
use App\Models\CampaignLog;
use App\Jobs\SendCampaignJob;
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

    // public function leadResponse($logId, $status)
    // {
    //     if (!in_array($status, ['interested','not_interested'])) {
    //         abort(404);
    //     }
    //     $log = CampaignLog::find($logId);
    //     if (!$log) {
    //         abort(404);
    //     }
    //     $log->status = $status;
    //     $log->save();
    //     $lead = Lead::find($log->lead_id);
    //     if ( $lead && $status == 'not_interested'){
    //         // GLOBAL UNSUBSCRIBE
    //         $lead->is_unsubscribed = true;
    //         $lead->save();
    //         // DELETE FUTURE PENDING MAILS
    //         CampaignLog::where('lead_id', $lead->id)
    //         ->update([
    //             'status' => 'Not Interested'
    //         ]);
    //     }
    //     return view('emails.response',compact('status'));
    // }

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
            Log::info('Email Opened', [
                'log_id' => $log->id,
                'opened_at' => now(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
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
}
