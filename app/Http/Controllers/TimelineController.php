<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Post;
use App\Models\User;

class TimelineController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Load authenticated user as an Eloquent model (best practice)
        $authId = Auth::id();
        $user = $authId ? User::with('following')->find($authId) : null;

        if (! $user) {
            Log::warning('TimelineController: no authenticated Eloquent user found', ['auth_id' => $authId]);
            $followingIds = [];
            $following = collect();
        } else {
                $followingIds = $user->following()->pluck('users.id')->toArray();
            $following = $user->following()->limit(10)->get();
        }

        // Include self in timeline (only include non-null IDs)
        $ids = array_filter(array_merge([$authId], $followingIds ?: []));

        $posts = Post::with('user', 'likes')
            ->withCount('likes')
            ->whereIn('user_id', $ids)
            ->latest()
            ->paginate(10);

        return view('timeline.index', compact('posts', 'following'));
    }
}