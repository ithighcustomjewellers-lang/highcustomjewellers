<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignLog extends Model
{
    protected $fillable = [
            'user_id',
            'lead_id',
            'sequence_id',
            'status',
            'scheduled_at',
            'sent_at'
    ];

    // 🔗 belongs to contact
    public function lead()
    {
        return $this->belongsTo(lead::class);
    }

    // 🔗 belongs to sequence
    public function sequence()
    {
        return $this->belongsTo(Sequence::class);
    }
}
