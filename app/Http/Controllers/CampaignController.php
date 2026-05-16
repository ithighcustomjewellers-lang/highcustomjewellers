<?php

namespace App\Http\Controllers;

use App\Models\Sequence;
use App\Models\CampaignLog;
use App\Jobs\SendCampaignJob;
use App\Models\Lead;

class CampaignController extends Controller
{
    public function start($id)
    {
        $lead = Lead::findOrFail($id);

        // =========================
        // ✅ USER WISE SEQUENCE
        // =========================
        $sequences = Sequence::where('user_id', $lead->user_id)
            ->whereRaw('UPPER(type) = ?', [strtoupper($lead->type)])
            ->orderBy('step')
            ->get();

        foreach ($sequences as $sequence) {

            // =========================
            // ❌ DUPLICATE CHECK
            // =========================
            $alreadyQueued = CampaignLog::where('user_id', $lead->user_id)
                ->where('lead_id', $lead->id)
                ->where('sequence_id', $sequence->id)
                ->exists();

            if ($alreadyQueued) {
                continue;
            }

            // =========================
            // ✅ SEND DATE
            // =========================
            $delay = now()->addDays((int) $sequence->gap_days);

            // =========================
            // ✅ CREATE LOG
            // =========================
            CampaignLog::create([
                'user_id' => $lead->user_id,
                'lead_id' => $lead->id,
                'sequence_id' => $sequence->id,
                'status' => 'pending',
                'scheduled_at' => $delay,
            ]);

            // =========================
            // ✅ DISPATCH JOB
            // =========================
            SendCampaignJob::dispatch(
                $lead->id,
                $sequence->id,
                $lead->user_id
            )->delay($delay);
        }

        return true;
    }
}
