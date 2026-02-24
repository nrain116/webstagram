<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ]);

        // Create the new user
        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Send email verification notification explicitly (this is optional since Laravel does this automatically)
        $user->sendEmailVerificationNotification();

        // Trigger the Registered event (this is done automatically)
        event(new \Illuminate\Auth\Events\Registered($user));

        // Log the user in
        Auth::login($user);

        // Redirect to the verification notice
        return redirect()->route('verification.notice')->with('status', 'Account created successfully! Please verify your email.');
    }
}
