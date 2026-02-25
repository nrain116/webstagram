{{-- resources/views/posts/show.blade.php --}}

@extends('layouts.app')

@section('title', 'Beitrag - WebmasterGram')

@section('content')
<div class="container mx-auto max-w-2xl mt-16 p-6 bg-white shadow-lg rounded-lg">
    <div class="mb-6 border rounded-lg overflow-hidden">
        <div class="p-4 flex items-start gap-3 bg-gray-50">
            <div class="flex-shrink-0">
                <img src="{{ optional($post->user)->profile_photo_url }}" alt="{{ optional($post->user)->username ?? 'Benutzer' }}" class="rounded-full" style="width:48px; height:48px;">
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <div>
                        @if($post->user)
                            <a href="{{ route('profile.show', $post->user) }}" class="font-semibold text-gray-800 hover:underline">{{ $post->user->username }}</a>
                        @else
                            <span class="font-semibold">Unbekannter Benutzer</span>
                        @endif
                        <div class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</div>
                    </div>
                </div>

                <p class="mt-3 text-gray-700">{{ $post->description }}</p>
            </div>
        </div>

        @if($post->image_url)
            <img src="{{ asset($post->image_url) }}" alt="Beitragsbild" class="w-full h-96 object-cover">
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
                        <button class="bg-red-500 text-white py-1 px-3 rounded">Gefällt mir nicht mehr</button>
                    @else
                        <button class="bg-blue-500 text-white py-1 px-3 rounded">Gefällt mir</button>
                    @endif
                </form>
            @else
                <a href="{{ route('login') }}" class="text-blue-500">Anmelden zum Liken</a>
            @endauth
        </div>
    </div>

    <a href="{{ route('timeline') }}" class="text-blue-500 hover:underline">Zurück zur Timeline</a>
</div>
@endsection
