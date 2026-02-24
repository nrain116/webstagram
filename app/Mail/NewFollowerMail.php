<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewFollowerMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $follower;

    public function __construct(User $follower)
    {
        $this->follower = $follower;
    }

    public function build()
    {
        return $this->subject('You have a new follower!')
                    ->markdown('emails.new_follower')
                    ->with([
                        'followerName' => $this->follower->username,
                        'followerEmail' => $this->follower->email,
                    ]);
    }
}