<?php

namespace App\Http\Controllers;

use App\Models\Sequence;
use App\Models\CampaignLog;
use App\Jobs\SendCampaignJob;
use App\Models\Lead;
use Illuminate\Support\Facades\Log;

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
        if (!in_array($status, ['interested','not_interested'])) {
            abort(404);
        }
        $log = CampaignLog::find($logId);
        if (!$log) {
            abort(404);
        }
        $log->status = $status;
        $log->save();
        $lead = Lead::find($log->lead_id);
        if ( $lead && $status == 'not_interested'){
            // GLOBAL UNSUBSCRIBE
            $lead->is_unsubscribed = true;
            $lead->save();
            // DELETE FUTURE PENDING MAILS
            CampaignLog::where('lead_id', $lead->id)
            ->update([
                'status' => 'not_interested'
            ]);
        }
        return view('emails.response',compact('status'));
    }


    public function trackOpen($logId)
    {
        Log::info('CampaignLog', [
            'campaign_log_id' => $logId
         ]);
        $log = CampaignLog::find($logId);
        log::info('CampaignLog found', [
            'campaign_log_id' => $logId,
            'exists' => (bool) $log
        ]);
        if ($log) {
            // only first open
            if (!$log->seen_at) {
                $log->seen_at = now();
                // status change only if sent
                if ($log->status == 'send') {
                    $log->status = 'seen';
                }
                $log->save();
            }
        }
        // 1x1 transparent pixel
        $pixel = base64_decode(
            'R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw=='
        );
        return response($pixel)->header('Content-Type', 'image/gif');
    }
}
