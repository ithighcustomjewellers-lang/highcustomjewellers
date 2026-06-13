<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmailLinkClick extends Model
{
    protected $table = 'email_link_clicks';

    protected $fillable = [
        'campaign_log_id', 'lead_id', 'user_id', 'sequence_id',
        'platform_name', 'destination_url', 'click_token',
        'clicked_at', 'ip_address', 'user_agent', 'click_count'
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
        'click_count' => 'integer'
    ];

    public function campaignLog()
    {
        return $this->belongsTo(CampaignLog::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function sequence()
    {
        return $this->belongsTo(Sequence::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
