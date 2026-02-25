{{-- resources/views/profile/profile.blade.php --}}

@extends('layouts.app')

@section('title', isset($user) ? $user->username . ' — Profil' : 'Profil')

@section('content')
<div class="container mx-auto max-w-3xl mt-16 p-6 bg-white shadow-lg rounded-lg">
    <div class="flex items-start gap-6">
        <div class="flex-shrink-0">
            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->username }}" class="rounded-full" style="width:96px; height:96px;">
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
                            <a href="{{ route('posts.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg">Neuer Beitrag</a>
                        @else
                            @php
                                $isFollowing = auth()->user()->following()->where('users.id', $user->id)->exists();
                            @endphp
                            <div class="follow-toggle-container" data-user-id="{{ $user->id }}">
                                @if($isFollowing)
                                    <form action="{{ route('unfollow', $user) }}" method="POST" class="inline follow-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white py-2 px-3 rounded follow-button">Nicht mehr folgen</button>
                                    </form>
                                @else
                                    <form action="{{ route('follow', $user) }}" method="POST" class="inline follow-form">
                                        @csrf
                                        <button type="submit" class="bg-blue-500 text-white py-2 px-3 rounded follow-button">Folgen</button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="mt-4 flex gap-6 text-sm text-gray-700">
                <div><strong>{{ $user->posts->count() }}</strong> Beiträge</div>
                <div id="follower-count"><strong>{{ $user->followers()->count() }}</strong> Follower</div>
                <div><strong>{{ $user->following()->count() }}</strong> Folge ich</div>
            </div>

            @auth
                @if(auth()->id() === $user->id)
                    <div class="mt-6 bg-gray-50 p-4 rounded">
                        <h4 class="font-medium mb-2">Profil bearbeiten</h4>
                        <form action="{{ route('profile.update', $user) }}" method="POST" class="mb-4">
                            @csrf
                            @method('PATCH')
                            <div class="flex gap-2">
                                <input type="text" name="username" value="{{ old('username', $user->username) }}" class="flex-1 px-3 py-2 border rounded" placeholder="Benutzername">
                                <button class="bg-blue-500 text-white px-4 rounded">Speichern</button>
                            </div>
                            @error('username')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                        </form>

                        <form action="{{ route('profile.photo', $user) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="flex items-center gap-3">
                                <input type="file" name="profile_photo_url" accept="image/*" class="">
                                <button class="bg-gray-200 px-3 py-1 rounded">Foto hochladen</button>
                            </div>
                            @error('profile_photo_url')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                        </form>
                    </div>
                @endif
            @endauth

            <hr class="my-6">

            <div>
                <h4 class="font-medium mb-3">Beiträge</h4>
                <div class="grid grid-cols-3 gap-3">
                    @forelse($user->posts ?? [] as $post)
                        <div class="border rounded overflow-hidden">
                            <img src="{{ asset($post->image_url) }}" alt="Beitrag" class="w-full h-32 object-cover">
                        </div>
                    @empty
                        <p class="text-gray-500">Noch keine Beiträge.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('submit', function(e) {
        if (e.target.classList.contains('follow-form')) {
            e.preventDefault();
            const form = e.target;
            const container = form.closest('.follow-toggle-container');
            const url = form.action;
            const method = form.querySelector('input[name="_method"]')?.value || 'POST';
            const token = form.querySelector('input[name="_token"]').value;

            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const userId = container.dataset.userId;
                    
                    if (method === 'DELETE') {
                        // Was unfollowed, show Follow button
                        container.innerHTML = `
                            <form action="/profile/${userId}/follow" method="POST" class="inline follow-form">
                                <input type="hidden" name="_token" value="${token}">
                                <button type="submit" class="bg-blue-500 text-white py-2 px-3 rounded follow-button">Folgen</button>
                            </form>
                        `;
                    } else {
                        // Was followed, show Unfollow button
                        container.innerHTML = `
                            <form action="/profile/${userId}/follow" method="POST" class="inline follow-form">
                                <input type="hidden" name="_token" value="${token}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="bg-red-500 text-white py-2 px-3 rounded follow-button">Nicht mehr folgen</button>
                            </form>
                        `;
                    }

                    // Update follower count if on profile page
                    const countEl = document.getElementById('follower-count');
                    if (countEl) {
                        let currentCount = parseInt(countEl.querySelector('strong').innerText);
                        countEl.querySelector('strong').innerText = method === 'DELETE' ? currentCount - 1 : currentCount + 1;
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
});
</script>
@endsection
