<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignLog extends Model
{
    protected $fillable = [
        'user_id',
        'lead_id',
        'sequence_id',
        'sent_at',
        'status',
        'variant'
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
