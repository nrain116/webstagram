<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class VerifyEmail extends VerifyEmailNotification implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        try {
            $verificationUrl = $this->verificationUrl($notifiable);
            Log::info('Generated Verification URL: ' . $verificationUrl); // Log the generated URL
        } catch (\Exception $e) {
            Log::error('Verification URL generation failed: ' . $e->getMessage(), [
                'user_id' => $notifiable->getKey(),
                'email' => $notifiable->getEmailForVerification(),
            ]);
            throw $e;
        }

        return (new MailMessage)
            ->subject('Verify Email Address')
            ->greeting('Hello!')
            ->line('Please click the button below to verify your email address.')
            ->action('Verify Email Address', $verificationUrl)
            ->line('If you did not create an account, no further action is required.')
            ->salutation('Best regards, ' . config('app.name'));
    }

    /**
     * Get the verification URL for the notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify', // The route name (you already defined this in routes)
            Carbon::now()->addMinutes(60), // URL expires in 60 minutes
            [
                'id' => $notifiable->getKey(), // User ID
                'hash' => sha1($notifiable->getEmailForVerification()) // Generate the hash using email
            ]
        );
    }
}