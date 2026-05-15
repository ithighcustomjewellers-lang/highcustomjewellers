<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
    'user_id',
    'email',
    'name',
    'lastname',
    'company_name',
    'type'
];
}
