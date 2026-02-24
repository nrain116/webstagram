<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified'); // require email verification
    }

    /**
     * Show the create post form.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a new post with optional image.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'description' => 'required|string|max:1000',
            'image' => 'nullable|image|max:5120', // 5MB max
        ]);

        $user = Auth::user();
        $imagePath = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $publicDir = public_path('images/posts');

            if (! File::exists($publicDir)) {
                File::makeDirectory($publicDir, 0755, true);
            }

            $filename = Str::slug($user->username . '-' . time()) . '.' . $file->getClientOriginalExtension();
            $file->move($publicDir, $filename);
            $imagePath = 'images/posts/' . $filename;
        }

        Post::create([
            'user_id' => $user->id,
            'description' => $data['description'],
            'image_path' => $imagePath,
        ]);

        return redirect()->route('timeline')->with('status', 'Post created successfully!');
    }

    /**
     * Show a single post.
     */
    public function show(Post $post)
    {
        $post->loadMissing(['user', 'likes']);

        return view('posts.show', compact('post'));
    }

    public function like(Post $post)
    {
        $user = Auth::user();

        // Check if the user has already liked the post
        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            // If the user has already liked the post, remove the like (unlike)
            $like->delete();
        } else {
            // If the user hasn't liked the post, add a like
            $post->likes()->create([
                'user_id' => $user->id,
            ]);
        }

        return back();  // Go back to the post's page
    }

    public function unlike(Post $post)
    {
        $user = Auth::user();
        $like = $post->likes()->where('user_id', $user->id)->first();
        if ($like) {
            $like->delete();
        }
        return back();
    }

    public function destroy(Post $post)
    {
        $user = Auth::user();
        if ($post->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($post->image_path && File::exists(public_path($post->image_path))) {
            File::delete(public_path($post->image_path));
        }

        $post->delete();

        return redirect()->route('timeline')->with('status', 'Post deleted successfully!');
    }
}
