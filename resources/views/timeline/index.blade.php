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
                <a href="{{ route('posts.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg">Beitrag hinzufügen</a>
            </div>

            @forelse($posts ?? [] as $post)
                <div class="mb-6 border rounded-lg overflow-hidden">
                    <div class="p-4 flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <img src="{{ optional($post->user)->profile_photo_url ?? asset('images/default-avatar.png') }}" alt="{{ optional($post->user)->username ?? 'Benutzer' }}" class="rounded-full" style="width:48px; height:48px;">
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <div>
                                    @if($post->user)
                                        <a href="{{ route('profile.show', $post->user) }}" class="font-semibold text-gray-800">{{ $post->user->username }}</a>
                                    @else
                                        <span class="font-semibold">{{ optional($post->user)->username ?? 'Benutzer' }}</span>
                                    @endif
                                    <div class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() ?? 'Gerade eben' }}</div>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">{{ $post->likes_count ?? 0 }} Likes</span>
                                </div>
                            </div>

                            <p class="mt-3 text-gray-700">{{ $post->description ?? 'Beitragbeschreibung' }}</p>

                            <div class="mt-4">
                                @auth
                                    @if($post->likedBy(auth()->user()))
                                        <form action="{{ route('unlike', $post) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="bg-red-500 text-white py-1 px-3 rounded">Gefällt mir nicht mehr</button>
                                        </form>
                                    @else
                                        <form action="{{ route('like', $post) }}" method="POST" class="inline">
                                            @csrf
                                            <button class="bg-blue-500 text-white py-1 px-3 rounded">Like</button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="text-blue-500">Anmelden zum Liken</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                    @if($post->image_url)
                        <img src="{{ asset($post->image_url) }}" alt="Beitragsbild" class="rounded-lg mt-2 max-w-xs mx-auto">
                    @endif
                    <!-- Delete Button -->
                    @if($post->user_id === auth()->id()) 
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded">Löschen</button>
                        </form>
                    @endif
                </div>
            @empty
                <p class="text-center text-gray-600">Noch keine Beiträge. Folge Benutzern, um deren Beiträge zu sehen!</p>
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
                <form action="{{ route('timeline') }}" method="GET" class="flex mb-4">
                    <input type="text" name="query" value="{{ $searchQuery ?? '' }}" placeholder="Benutzer suchen..." class="flex-1 border px-2 py-1 rounded">
                    <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded ml-2">Suchen</button>
                </form>

                <h3 class="font-semibold mb-3">Ich folge</h3>
                <ul class="space-y-2 mb-6">
                    @forelse($following ?? [] as $user)
                        <li class="flex items-center justify-between hover:bg-white p-2 rounded">
                            <a href="{{ route('profile.show', $user) }}" class="flex items-center gap-3 text-gray-800">
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->username ?? 'Benutzer' }}" class="rounded-full" style="width:36px; height:36px;">
                                <span>{{ $user->username ?? 'Benutzer' }}</span>
                            </a>
                            <div class="follow-toggle-container" data-user-id="{{ $user->id }}">
                                <form action="{{ route('unfollow', $user) }}" method="POST" class="inline follow-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 text-xs font-semibold follow-button">Entfolgen</button>
                                </form>
                            </div>
                        </li>
                    @empty
                        <li class="text-gray-500">Du folgst noch niemandem.</li>
                    @endforelse
                </ul>

                @if(isset($searchResults))
                    <h3 class="font-semibold mb-3 border-t pt-4">Suchergebnisse</h3>
                    <ul class="space-y-2">
                        @forelse($searchResults as $result)
                            <li class="flex items-center justify-between hover:bg-white p-2 rounded">
                                <a href="{{ route('profile.show', $result) }}" class="flex items-center gap-3 text-gray-800">
                                    <img src="{{ $result->profile_photo_url }}" alt="{{ $result->username ?? 'Benutzer' }}" class="rounded-full" style="width:36px; height:36px;">
                                    <span>{{ $result->username ?? 'Benutzer' }}</span>
                                </a>
                                @auth
                                    @if(auth()->id() !== $result->id)
                                        @php
                                            $isFollowing = auth()->user()->following()->where('users.id', $result->id)->exists();
                                        @endphp
                                        <div class="follow-toggle-container" data-user-id="{{ $result->id }}">
                                            @if($isFollowing)
                                                <form action="{{ route('unfollow', $result) }}" method="POST" class="inline follow-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 text-xs font-semibold follow-button">Entfolgen</button>
                                                </form>
                                            @else
                                                <form action="{{ route('follow', $result) }}" method="POST" class="inline follow-form">
                                                    @csrf
                                                    <button type="submit" class="text-blue-500 text-xs font-semibold follow-button">Folgen</button>
                                                </form>
                                            @endif
                                        </div>
                                    @endif
                                @endauth
                            </li>
                        @empty
                            <li class="text-gray-500">Keine Benutzer gefunden.</li>
                        @endforelse
                    </ul>
                @endif
            </div>
        </aside>
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
                                <button type="submit" class="text-blue-500 text-xs font-semibold follow-button">Folgen</button>
                            </form>
                        `;
                    } else {
                        // Was followed, show Unfollow button
                        container.innerHTML = `
                            <form action="/profile/${userId}/follow" method="POST" class="inline follow-form">
                                <input type="hidden" name="_token" value="${token}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="text-red-500 text-xs font-semibold follow-button">Entfolgen</button>
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