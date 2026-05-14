<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Sequence;
use App\Models\CampaignLog;
use App\Jobs\SendCampaignJob;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    public function start($id)
    {
        $lead = Lead::findOrFail($id);

        $sequences = Sequence::whereRaw('UPPER(type) = ?', [strtoupper($lead->type)])
            ->orderBy('step')
            ->get();

        $delay = now();

        foreach ($sequences as $sequence) {

            $alreadySent = CampaignLog::where('contact_id', $lead->id)
                ->where('sequence_id', $sequence->id)
                ->exists();

            if ($alreadySent) continue;

            // cumulative delay
            $delay = $delay->copy()->addDays((int) $sequence->gap_days);

            // SendCampaignJob::dispatch($contact, $sequence)
            //     ->delay($delay);

            SendCampaignJob::dispatch($lead, $sequence, Auth::id())
            ->delay($delay);
        }

        return "Campaign Started";
    }
}
