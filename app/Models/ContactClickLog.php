<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactClickLog extends Model
{
   // app/Models/ContactClickLog.php
    protected $fillable = ['user_id', 'social_link_id', 'clicked_at', 'ip'];
}
