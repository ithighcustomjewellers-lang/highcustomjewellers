<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'company_name',
        'type'
    ];

     // 🔗 relation with logs
    public function logs()
    {
        return $this->hasMany(CampaignLog::class);
    }
}
