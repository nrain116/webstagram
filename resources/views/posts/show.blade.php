@extends('layouts.app')

@section('title', 'Beitrag - WebmasterGram')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <article class="bg-white border border-gray-100 rounded-3xl shadow-sm overflow-hidden transition-all">
        <!-- Post Header -->
        <div class="p-6 flex items-center justify-between border-b border-gray-50">
            <div class="flex items-center gap-4">
                <a href="{{ $post->user ? route('profile.show', $post->user) : '#' }}" class="relative group">
                    <img src="{{ optional($post->user)->profile_photo_url ?? asset('images/default-avatar.png') }}" 
                         alt="{{ optional($post->user)->username ?? 'Benutzer' }}" 
                         class="w-12 h-12 rounded-2xl object-cover ring-4 ring-gray-50 group-hover:ring-blue-50 transition-all">
                </a>
                <div>
                    @if($post->user)
                        <a href="{{ route('profile.show', $post->user) }}" class="text-lg font-bold text-gray-900 hover:text-blue-600 transition-colors">
                            {{ $post->user->username }}
                        </a>
                    @else
                        <span class="text-lg font-bold text-gray-500 italic">Gelöschter Benutzer</span>
                    @endif
                    <div class="text-xs text-gray-500 font-bold uppercase tracking-wider">
                        {{ $post->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>

            @if($post->user_id === auth()->id())
                <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Möchten Sie diesen Beitrag wirklich löschen?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all" title="Löschen">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            @endif
        </div>

        <!-- Post Content -->
        <div class="p-6">
            <p class="text-lg text-gray-800 leading-relaxed">{{ $post->description }}</p>
        </div>

        @if($post->image_url)
            <div class="bg-gray-50 border-y border-gray-50">
                <img src="{{ asset($post->image_url) }}" alt="Beitragsbild" class="w-full h-auto max-h-[600px] object-contain mx-auto">
            </div>
        @endif

        <!-- Post Actions -->
        <div class="p-6 bg-gray-50/50 flex items-center justify-between">
            <div class="flex items-center gap-6">
                @auth
                    @php $liked = $post->likedBy(auth()->user()); @endphp
                    <form action="{{ $liked ? route('unlike', $post) : route('like', $post) }}" method="POST" class="inline">
                        @csrf
                        @if($liked) @method('DELETE') @endif
                        <button type="submit" class="flex items-center gap-2.5 px-5 py-2.5 rounded-2xl transition-all shadow-sm {{ $liked ? 'text-red-600 bg-white' : 'text-gray-600 bg-white hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 {{ $liked ? 'fill-current' : '' }}" fill="{{ $liked ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span class="font-bold text-lg">{{ $post->likes_count ?? $post->likes()->count() }}</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="flex items-center gap-2.5 px-5 py-2.5 bg-white rounded-2xl text-gray-500 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span class="font-bold text-lg">{{ $post->likes()->count() }}</span>
                    </a>
                @endauth
            </div>

            <a href="{{ route('timeline') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                <span>Timeline</span>
            </a>
        </div>
    </article>
</div>
@endsection
