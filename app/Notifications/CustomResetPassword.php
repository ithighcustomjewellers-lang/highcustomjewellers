<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;



class CustomResetPassword extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url('/reset-password/'.$this->token.'?email='.$notifiable->email);

        return (new MailMessage)
            ->subject('Reset Your Password 🔐')
            ->greeting('Hello '.$notifiable->name)
            ->line('Click below to reset your password.')
            ->action('Reset Password', $url)
            ->line('If you did not request, ignore this email.');

        // return (new MailMessage)
        //     ->subject('Reset Password')
        //     ->view('emails.reset-password', [
        //         'url' => $url,
        //         'user' => $notifiable
        //     ]);
    }
}
