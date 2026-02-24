@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-5xl mt-16 p-6 bg-white shadow-lg rounded-lg">
    <div class="flex gap-6">
        <!-- Left: Posts Feed -->
        <div class="w-2/3">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-14">
                    <h1 class="text-xl font-bold">Timeline</h1>
                </div>
                <a href="{{ route('posts.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg">Add Post</a>
            </div>

            @forelse($posts ?? [] as $post)
                <div class="mb-6 border rounded-lg overflow-hidden">
                    <div class="p-4 flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <img src="{{ optional($post->user)->profile_photo_url ?? asset('images/default-avatar.png') }}" alt="{{ optional($post->user)->username ?? 'User' }}" class="rounded-full" style="width:48px; height:48px;">
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <div>
                                    @if($post->user)
                                        <a href="{{ route('profile.show', $post->user) }}" class="font-semibold text-gray-800">{{ $post->user->username }}</a>
                                    @else
                                        <span class="font-semibold">{{ optional($post->user)->username ?? 'User' }}</span>
                                    @endif
                                    <div class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() ?? 'Just now' }}</div>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">{{ $post->likes_count ?? 0 }} Likes</span>
                                </div>
                            </div>

                            <p class="mt-3 text-gray-700">{{ $post->description ?? 'Post description' }}</p>

                            <div class="mt-4">
                                @auth
                                    @if($post->likedBy(auth()->user()))
                                        <form action="{{ route('unlike', $post) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="bg-red-500 text-white py-1 px-3 rounded">Unlike</button>
                                        </form>
                                    @else
                                        <form action="{{ route('like', $post) }}" method="POST" class="inline">
                                            @csrf
                                            <button class="bg-blue-500 text-white py-1 px-3 rounded">Like</button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="text-blue-500">Sign in to like</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                    @if($post->image_path)
                        <img src="{{ asset($post->image_path) }}" alt="Post Image" class="rounded-lg mt-2 max-w-xs mx-auto">
                    @endif
                    <!-- Delete Button -->
                    @if($post->user_id === auth()->id()) 
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded">Delete</button>
                        </form>
                    @endif
                </div>
            @empty
                <p class="text-center text-gray-600">No posts yet. Follow users to see their posts!</p>
            @endforelse

            {{-- Pagination --}}
            <div class="mt-6">
                @if(method_exists($posts, 'links'))
                    {{ $posts->links() }}
                @endif
            </div>
        </div>

        <!-- Right: Sidebar -->
        <aside class="w-1/3">
            <div class="p-4 bg-gray-50 rounded-lg">
                <!-- Search Users Form -->
                <form action="{{ route('users.search') }}" method="GET" class="flex mb-4">
                    <input type="text" name="query" placeholder="Search users..." class="flex-1 border px-2 py-1 rounded">
                    <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded ml-2">Search</button>
                </form>

                <h3 class="font-semibold mb-3">Following</h3>
                <ul class="space-y-2">
                    @forelse($following ?? [] as $user)
                        <li>
                            <a href="{{ route('profile.show', $user) }}" class="flex items-center gap-3 text-gray-800 hover:bg-white p-2 rounded">
                                <img src="{{ $user->profile_photo_url ?? asset('images/default-avatar.png') }}" alt="{{ $user->username ?? 'User' }}" class="rounded-full" style="width:36px; height:36px;">
                                <span>{{ $user->username ?? 'User' }}</span>
                            </a>
                        </li>
                    @empty
                        <li class="text-gray-500">You are not following anyone yet.</li>
                    @endforelse
                </ul>
            </div>
        </aside>
    </div>
</div>
@endsection