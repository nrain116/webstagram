<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class VerifyEmail extends Mailable
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        // Create the verification URL
        $verificationUrl = $this->getVerificationUrl();

        return $this->subject('Verify Your Email Address')
                    ->view('emails.verify', ['verificationUrl' => $verificationUrl]);
    }

    protected function getVerificationUrl()
    {
        return URL::temporarySignedRoute(
            'verification.verify', // route name
            Carbon::now()->addMinutes(60), // expiration time for the link
            [
                'id' => $this->user->getKey(),
                'hash' => sha1($this->user->getEmailForVerification()),
            ]
        );
    }
}