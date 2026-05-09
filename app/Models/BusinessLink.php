<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'whatsapp_link',
        'telegram_link',
        'business_link',
        'company_logo',
    ];
}
