<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Sequence;
use App\Models\CampaignLog;
use App\Jobs\SendCampaignJob;

class CampaignController extends Controller
{
    public function start($id)
    {
        $contact = Contact::findOrFail($id);

        $sequences = Sequence::whereRaw('UPPER(type) = ?', [strtoupper($contact->type)])
            ->orderBy('step')
            ->get();

        $delay = now();

        foreach ($sequences as $sequence) {

            $alreadySent = CampaignLog::where('contact_id', $contact->id)
                ->where('sequence_id', $sequence->id)
                ->exists();

            if ($alreadySent) continue;

            // cumulative delay
            $delay = $delay->copy()->addDays((int) $sequence->gap_days);

            // SendCampaignJob::dispatch($contact, $sequence)
            //     ->delay($delay);

            SendCampaignJob::dispatch($contact, $sequence, auth()->id())
            ->delay($delay);
        }

        return "Campaign Started";
    }
}
