<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MultiQrLink extends Model
{
    protected $fillable = [
        'code',
        'links'
    ];
}
