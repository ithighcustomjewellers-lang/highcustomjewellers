<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignLog extends Model
{
    protected $fillable = [
        'contact_id',
        'sequence_id',
        'sent_at',
        'status'
    ];

    // 🔗 belongs to contact
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    // 🔗 belongs to sequence
    public function sequence()
    {
        return $this->belongsTo(Sequence::class);
    }
}
