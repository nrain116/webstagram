<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function __construct()
    {
        // Require authentication for mutating actions
        $this->middleware('auth')->only(['update', 'photo', 'follow', 'unfollow']);
    }

    /**
     * Show a user's profile.
     */
    public function show(User $user)
    {
        $user->loadMissing(['posts' => function ($q) {
            $q->latest();
        }]);

        return view('profile.profile', compact('user'));
    }

    /**
     * Update profile fields (username).
     */
    public function update(Request $request, User $user)
    {
        // only owner can update
        if (Auth::id() !== $user->id) {
            abort(403);
        }

        $data = $request->validate([
            'username' => 'required|string|min:3|max:50|unique:users,username,' . $user->id,
        ]);

        $user->update($data);

        return redirect()->back()->with('status', 'Profile updated.');
    }

    /**
     * Upload or replace profile photo. Stores file under public/images and saves relative path.
     */
    public function photo(Request $request, User $user)
    {
        if (Auth::id() !== $user->id) {
            abort(403);
        }

        $request->validate([
            'profile_photo' => 'required|image|max:5120',
        ]);

        $file = $request->file('profile_photo');

        $publicDir = public_path('images');
        if (! File::exists($publicDir)) {
            File::makeDirectory($publicDir, 0755, true);
        }

        // remove previous file if it exists and is inside public/images
        if ($user->profile_picture && File::exists(public_path($user->profile_picture))) {
            try {
                File::delete(public_path($user->profile_picture));
            } catch (\Throwable $e) {
                // ignore deletion errors
            }
        }

        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                    . '-' . time() . '.' . $file->getClientOriginalExtension();

        $file->move($publicDir, $filename);

        $user->profile_picture = 'images/' . $filename;
        $user->save();

        return redirect()->back()->with('status', 'Profile photo updated.');
    }

    /**
     * Follow a user.
     */
    public function follow(User $user)
    {
        /** @var \App\Models\User|null $me */
        $me = Auth::user();
        if (! $me instanceof User || $me->id === $user->id) {
            return redirect()->back();
        }

        $me->following()->syncWithoutDetaching([$user->id]);

        return redirect()->back()->with('status', 'Now following ' . $user->username);
    }

    /**
     * Unfollow a user.
     */
    public function unfollow(User $user)
    {
        /** @var \App\Models\User|null $me */
        $me = Auth::user();
        if (! $me instanceof User || $me->id === $user->id) {
            return redirect()->back();
        }

        $me->following()->detach($user->id);

        return redirect()->back()->with('status', 'Unfollowed ' . $user->username);
    }
}
