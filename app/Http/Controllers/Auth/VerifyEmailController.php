<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    // Ensure that the user is authenticated before accessing any of the methods.
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the email verification view if the user's email is not verified.
     */
    public function show(Request $request)
    {
        // If the authenticated user's email is already verified, redirect to timeline
        if ($request->user() && $request->user()->hasVerifiedEmail()) {
            return redirect()->route('timeline');
        }

        // Otherwise, show the verification prompt
        return view('auth.verify-email');
    }

    /**
     * Verify the user's email address.
     */
    public function verify(EmailVerificationRequest $request)
    {
        // If already verified, redirect to the timeline with a status
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('timeline')->with('status', 'Email already verified.');
        }

        // Mark the user's email as verified and fire the Verified event
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // Redirect to the timeline with a success message
        return redirect()->route('timeline')->with('status', 'Email verified successfully!');
    }

    /**
     * Resend the email verification link if the user's email is not verified yet.
     */
    public function resend(Request $request)
    {
        // If the user is already verified, just redirect them to the timeline
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('timeline');
        }

        // Send the email verification notification to the user
        $request->user()->sendEmailVerificationNotification();

        // Provide feedback to the user that the verification link was sent
        return back()->with('status', 'Verification link sent!');
    }
}