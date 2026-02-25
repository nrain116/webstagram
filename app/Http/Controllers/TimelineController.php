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

    public function index(\Illuminate\Http\Request $request)
    {
        // Load authenticated user
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

        // Search logic
        $searchResults = null;
        $searchQuery = $request->input('query');
        if ($searchQuery) {
            $searchResults = User::where('username', 'like', "%{$searchQuery}%")
                                 ->take(10)
                                 ->get();
        }

        
        $ids = array_filter(array_merge([$authId], $followingIds ?: []));

        $posts = Post::with('user', 'likes')
            ->withCount('likes')
            ->whereIn('user_id', $ids)
            ->latest()
            ->paginate(10);

        return view('timeline.index', compact('posts', 'following', 'searchResults', 'searchQuery'));
    }
}