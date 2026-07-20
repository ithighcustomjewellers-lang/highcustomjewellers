<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sequence extends Model
{


    protected $fillable = [
        'user_id',
        'step',
        'gap_days',
        'variant',
        'type',
        'subject',
        'existing_company_logo',
        'image_type',
        'logo_position',
        'message',
        'hero_image',
        'attachments_image',
        'attachment_name',
        'attachment_size',
        'whatsapp_link',
        'action_links',
        'hero_image_link'
    ];

    protected $casts = [
        'action_links' => 'array',
    ];

    // 🔗 relation with attachments
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    // 🔗 relation with logs
    public function logs()
    {
        return $this->hasMany(CampaignLog::class);
    }

    public function campaignLogs()
    {
        return $this->hasMany(CampaignLog::class);
    }

}
