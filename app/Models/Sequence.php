<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sequence extends Model
{
    protected $fillable = [
        'step',
        'subject',
        'message',
        'gap_days',
        'variant',
        'type',
        'hero_image',
        'whatsapp_link',
        'telegram_link',
        'business_link',
        'attachments_image',
        'attachment_name',
        'attachment_size'
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

}
