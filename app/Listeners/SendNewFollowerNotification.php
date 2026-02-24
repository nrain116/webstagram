<?php

namespace App\Listeners;

use App\Events\UserFollowed;
use App\Mail\NewFollowerMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendNewFollowerNotification implements ShouldQueue
{
    public function handle(UserFollowed $event)
    {
        // Mail an die Person, die gefolgt wird
        Mail::to($event->following->email)
            ->queue(new NewFollowerMail($event->follower));
    }
}
