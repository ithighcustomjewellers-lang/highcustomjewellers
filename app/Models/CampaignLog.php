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
            'sent_at',
            'tracking_token',
            'open_token',
            'seen_at',
            'ip_address',
            'user_agent'
    ];

    // 🔗 belongs to contact
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    // 🔗 belongs to sequence
    public function sequence()
    {
        return $this->belongsTo(Sequence::class);
    }

    public function linkClicks()
    {
        return $this->hasMany(EmailLinkClick::class);
    }
}
