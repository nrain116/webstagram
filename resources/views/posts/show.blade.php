{{-- resources/views/posts/show.blade.php --}}

@extends('layouts.app')

@section('title', 'Post - WebmasterGram')

@section('content')
<div class="container mx-auto max-w-2xl mt-16 p-6 bg-white shadow-lg rounded-lg">
    <div class="mb-6 border rounded-lg overflow-hidden">
        <div class="p-4 flex items-start gap-3 bg-gray-50">
            <div class="flex-shrink-0">
                <img src="{{ optional($post->user)->profile_photo_url ?? asset('images/default-avatar.png') }}" alt="{{ optional($post->user)->username ?? 'User' }}" class="rounded-full" style="width:48px; height:48px;">
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <div>
                        @if($post->user)
                            <a href="{{ route('profile.show', $post->user) }}" class="font-semibold text-gray-800 hover:underline">{{ $post->user->username }}</a>
                        @else
                            <span class="font-semibold">Unknown User</span>
                        @endif
                        <div class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</div>
                    </div>
                </div>

                <p class="mt-3 text-gray-700">{{ $post->description }}</p>
            </div>
        </div>

        @if($post->image)
            <img src="{{ asset($post->image) }}" alt="Post image" class="w-full h-96 object-cover">
        @endif

        <div class="p-4 bg-gray-50">
            <div class="mb-3 text-sm text-gray-600">
                <strong>{{ $post->likes()->count() }}</strong> {{ Str::plural('Like', $post->likes()->count()) }}
            </div>

            @auth
                <form action="{{ route('like', $post) }}" method="POST" class="inline">
                    @csrf
                    @if($post->likedBy(auth()->user()))
                        @method('DELETE')
                        <button class="bg-red-500 text-white py-1 px-3 rounded">Unlike</button>
                    @else
                        <button class="bg-blue-500 text-white py-1 px-3 rounded">Like</button>
                    @endif
                </form>
            @else
                <a href="{{ route('login') }}" class="text-blue-500">Sign in to like</a>
            @endauth
        </div>
    </div>

    <a href="{{ route('timeline') }}" class="text-blue-500 hover:underline">Back to Timeline</a>
</div>
@endsection
