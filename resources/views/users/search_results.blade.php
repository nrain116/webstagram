@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-5xl mt-16 p-6 bg-white shadow-lg rounded-lg">
    <h1 class="text-xl font-bold mb-4">Search results for "{{ $query }}"</h1>

    @forelse($users as $user)
        <div class="mb-2 border rounded p-2 flex items-center gap-3">
            <img src="{{ $user->profile_photo_url ?? asset('images/default-avatar.png') }}" alt="{{ $user->username }}" class="rounded-full w-12 h-12">
            <a href="{{ route('profile.show', $user) }}" class="font-semibold text-gray-800">{{ $user->username }}</a>
        </div>
    @empty
        <p class="text-gray-600">No users found.</p>
    @endforelse
</div>
@endsection