<?php

namespace App\Models;
use App\Notifications\AdminCustomResetPassword;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function adminSendPasswordResetNotification($token)
    {
        $this->notify(new AdminCustomResetPassword($token));
    }
}
