<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Exception;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login = $request->input('login');
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [$field => $login, 'password' => $password];

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if (! $user->hasVerifiedEmail()) {
                // Redirect to the verification page if the email is not verified
                return redirect()->route('verification.notice');
            }

            return redirect()->intended(route('timeline'));
        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('login', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome');
    }

    public function redirectToGitHub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGitHubCallback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();
            $user = User::where('email', $githubUser->getEmail())->first();

            if (!$user) {
                $username = Str::slug($githubUser->getNickname() ?: 'user_' . Str::random(8), '-'); 

                $user = User::create([
                    'github_id' => $githubUser->getId(),
                    'email' => $githubUser->getEmail(),
                    'profile_picture_path' => $githubUser->getAvatar(),
                    'username' => $username, 
                    'password' => null,
                ]);
            }

            Auth::login($user);

            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            return redirect()->route('timeline');
        } catch (Exception $e) {
            return redirect('login')->withErrors(['error' => 'Failed to login with GitHub: ' . $e->getMessage()]);
        }
    }
}