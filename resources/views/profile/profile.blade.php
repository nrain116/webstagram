{{-- resources/views/profile/profile.blade.php --}}

@extends('layouts.app')

@section('title', isset($user) ? $user->username . ' — Profile' : 'Profile')

@section('content')
<div class="container mx-auto max-w-3xl mt-16 p-6 bg-white shadow-lg rounded-lg">
    <div class="flex items-start gap-6">
        <div class="flex-shrink-0">
            <img src="{{ $user->profile_photo_url ?? asset('images/default-avatar.png') }}" alt="{{ $user->username }}" class="rounded-full" style="width:96px; height:96px;">
        </div>

        <div class="flex-1">
            <div class="flex items-center gap-4">
                <div>
                    <h2 class="text-2xl font-semibold">{{ $user->username }}</h2>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>

                <div class="ml-auto">
                    @auth
                        @if(auth()->id() === $user->id)
                            <a href="{{ route('posts.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg">New Post</a>
                        @else
                            @php
                                $isFollowing = auth()->user()->following()->where('users.id', $user->id)->exists();
                            @endphp
                            @if($isFollowing)
                                <form action="{{ route('unfollow', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-500 text-white py-2 px-3 rounded">Unfollow</button>
                                </form>
                            @else
                                <form action="{{ route('follow', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="bg-blue-500 text-white py-2 px-3 rounded">Follow</button>
                                </form>
                            @endif
                        @endif
                    @endauth
                </div>
            </div>

            <div class="mt-4 flex gap-6 text-sm text-gray-700">
                <div><strong>{{ $user->posts->count() }}</strong> Posts</div>
                <div><strong>{{ $user->followers()->count() }}</strong> Followers</div>
                <div><strong>{{ $user->following()->count() }}</strong> Following</div>
            </div>

            @auth
                @if(auth()->id() === $user->id)
                    <div class="mt-6 bg-gray-50 p-4 rounded">
                        <h4 class="font-medium mb-2">Edit Profile</h4>
                        <form action="{{ route('profile.update', $user) }}" method="POST" class="mb-4">
                            @csrf
                            @method('PATCH')
                            <div class="flex gap-2">
                                <input type="text" name="username" value="{{ old('username', $user->username) }}" class="flex-1 px-3 py-2 border rounded" placeholder="Username">
                                <button class="bg-blue-500 text-white px-4 rounded">Save</button>
                            </div>
                            @error('username')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                        </form>

                        <form action="{{ route('profile.photo', $user) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="flex items-center gap-3">
                                <input type="file" name="profile_photo" accept="image/*" class="">
                                <button class="bg-gray-200 px-3 py-1 rounded">Upload Photo</button>
                            </div>
                            @error('profile_photo')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                        </form>
                    </div>
                @endif
            @endauth

            <hr class="my-6">

            <div>
                <h4 class="font-medium mb-3">Posts</h4>
                <div class="grid grid-cols-3 gap-3">
                    @forelse($user->posts ?? [] as $post)
                        <div class="border rounded overflow-hidden">
                            <img src="{{ asset($post->image_path) }}" alt="Post" class="w-full h-32 object-cover">
                        </div>
                    @empty
                        <p class="text-gray-500">No posts yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
