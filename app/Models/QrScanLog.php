<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrScanLog extends Model
{
    // app/Models/QrScanLog.php
    protected $fillable = ['user_id', 'scanned_at', 'ip'];
}
