<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Sequence;
use App\Jobs\SendCampaignJob;

class CampaignController extends Controller
{
    public function start($id)
    {
        $contact = Contact::findOrFail($id);

        // 👉 user type ke hisab se sequence
        $sequences = Sequence::where('type', $contact->type)
            ->orderBy('id')
            ->get();

        foreach ($sequences as $sequence) {

            // ⏱️ delay calculate
            $delay = now()->addDays($sequence->gap_days);

            // 🚀 job dispatch
            dispatch( (new SendCampaignJob($contact, $sequence))
                    ->delay($delay)
            );
        }

        return "Campaign Started for " . $contact->name;
    }
}
