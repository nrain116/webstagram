@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Posts Feed (Main Content) -->
        <div class="lg:col-span-8 space-y-8">
            <!-- Header Section -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-500 p-2 rounded-xl shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Timeline</h1>
                </div>
                <a href="{{ route('posts.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold transition-all shadow-sm hover:shadow-md active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Neuer Beitrag</span>
                </a>
            </div>

            <!-- Feed -->
            <div class="space-y-6">
                @forelse($posts ?? [] as $post)
                    <article class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden transition-all hover:border-gray-200">
                        <!-- Post Header -->
                        <div class="p-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <a href="{{ $post->user ? route('profile.show', $post->user) : '#' }}" class="relative group">
                                    <img src="{{ optional($post->user)->profile_photo_url ?? asset('images/default-avatar.png') }}" 
                                         alt="{{ optional($post->user)->username ?? 'Benutzer' }}" 
                                         class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-50 group-hover:ring-blue-100 transition-all">
                                </a>
                                <div>
                                    @if($post->user)
                                        <a href="{{ route('profile.show', $post->user) }}" class="font-bold text-gray-900 hover:text-blue-600 transition-colors">
                                            {{ $post->user->username }}
                                        </a>
                                    @else
                                        <span class="font-bold text-gray-500 italic">Gelöschter Benutzer</span>
                                    @endif
                                    <div class="text-xs text-gray-500 font-medium">
                                        {{ $post->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>

                            @if($post->user_id === auth()->id())
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Möchten Sie diesen Beitrag wirklich löschen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Löschen">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- Post Content -->
                        <div class="px-4 pb-3">
                            <p class="text-gray-800 leading-relaxed">{{ $post->description }}</p>
                        </div>

                        @if($post->image_url)
                            <div class="relative bg-gray-50 border-y border-gray-50">
                                <img src="{{ asset($post->image_url) }}" alt="Beitragsbild" class="w-full h-auto max-h-[500px] object-contain mx-auto">
                            </div>
                        @endif

                        <!-- Post Actions -->
                        <div class="p-4 bg-gray-50/50 flex items-center justify-between border-t border-gray-50">
                            <div class="flex items-center gap-4">
                                @auth
                                    @php $liked = $post->likedBy(auth()->user()); @endphp
                                    <form action="{{ $liked ? route('unlike', $post) : route('like', $post) }}" method="POST" class="inline">
                                        @csrf
                                        @if($liked) @method('DELETE') @endif
                                        <button type="submit" class="flex items-center gap-2 px-3 py-1.5 rounded-full transition-all {{ $liked ? 'text-red-600 bg-red-50' : 'text-gray-600 hover:bg-gray-100' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ $liked ? 'fill-current' : '' }}" fill="{{ $liked ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                            <span class="font-bold text-sm">{{ $post->likes_count ?? 0 }}</span>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="flex items-center gap-2 text-gray-500 hover:text-blue-600 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        <span class="font-bold text-sm">{{ $post->likes_count ?? 0 }}</span>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="text-center py-16 bg-white border-2 border-dashed border-gray-200 rounded-3xl">
                        <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Noch keine Beiträge</h3>
                        <p class="text-gray-500 mt-1 max-w-xs mx-auto">Folge anderen Benutzern, um deren Beiträge hier zu sehen!</p>
                        <a href="{{ route('timeline') }}" class="mt-6 inline-flex text-blue-600 font-semibold hover:underline">Entdecken</a>
                    </div>
                @endforelse

                <div class="mt-8">
                    @if(method_exists($posts, 'links'))
                        {{ $posts->links() }}
                    @endif
                </div>
            </div>
        </div>

        <!-- Right: Sidebar -->
        <aside class="lg:col-span-4 space-y-6">
            <!-- Search Widget -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Benutzer suchen
                </h3>
                <form action="{{ route('timeline') }}" method="GET" class="relative group">
                    <input type="text" name="query" value="{{ $searchQuery ?? '' }}" 
                           placeholder="Name eingeben..." 
                           class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 pl-4 pr-12 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 text-gray-400 hover:text-blue-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Following -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Ich folge
                </h3>
                <div class="space-y-4">
                    @forelse($following ?? [] as $user)
                        <div class="flex items-center justify-between group">
                            <a href="{{ route('profile.show', $user) }}" class="flex items-center gap-3">
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->username }}" class="w-9 h-9 rounded-full object-cover">
                                <span class="text-sm font-semibold text-gray-700 group-hover:text-blue-600 transition-colors">{{ $user->username }}</span>
                            </a>
                            <div class="follow-toggle-container" data-user-id="{{ $user->id }}">
                                <form action="{{ route('unfollow', $user) }}" method="POST" class="inline follow-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-600 transition-colors px-2 py-1 hover:bg-red-50 rounded-lg">
                                        Unfollow
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 py-2 italic text-center">Du folgst noch niemandem.</p>
                    @endforelse
                </div>
            </div>

            <!-- Search Results Widget -->
            @if(isset($searchResults))
                <div class="bg-white p-6 rounded-2xl border border-blue-100 shadow-sm ring-1 ring-blue-50">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Suchergebnisse
                    </h3>
                    <div class="space-y-4">
                        @forelse($searchResults as $result)
                            <div class="flex items-center justify-between">
                                <a href="{{ route('profile.show', $result) }}" class="flex items-center gap-3">
                                    <img src="{{ $result->profile_photo_url }}" alt="{{ $result->username }}" class="w-9 h-9 rounded-full object-cover">
                                    <span class="text-sm font-semibold text-gray-700 hover:text-blue-600 transition-colors">{{ $result->username }}</span>
                                </a>
                                @auth
                                    @if(auth()->id() !== $result->id)
                                        @php $isFollowing = auth()->user()->following()->where('users.id', $result->id)->exists(); @endphp
                                        <div class="follow-toggle-container" data-user-id="{{ $result->id }}">
                                            @if($isFollowing)
                                                <form action="{{ route('unfollow', $result) }}" method="POST" class="inline follow-form">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-600 transition-colors px-2 py-1 rounded-lg">Unfollow</button>
                                                </form>
                                            @else
                                                <form action="{{ route('follow', $result) }}" method="POST" class="inline follow-form">
                                                    @csrf
                                                    <button type="submit" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors px-3 py-1 bg-blue-50 rounded-lg">Follow</button>
                                                </form>
                                            @endif
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 py-2 italic text-center">Keine Benutzer gefunden.</p>
                        @endforelse
                    </div>
                </div>
            @endif
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
                        container.innerHTML = `
                            <form action="/profile/${userId}/follow" method="POST" class="inline follow-form">
                                <input type="hidden" name="_token" value="${token}">
                                <button type="submit" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors px-3 py-1 bg-blue-50 rounded-lg">Follow</button>
                            </form>
                        `;
                    } else {
                        container.innerHTML = `
                            <form action="/profile/${userId}/follow" method="POST" class="inline follow-form">
                                <input type="hidden" name="_token" value="${token}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-600 transition-colors px-2 py-1 rounded-lg">Unfollow</button>
                            </form>
                        `;
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
});
</script>
@endsection
