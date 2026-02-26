@extends('layouts.app')

@section('title', isset($user) ? $user->username . ' — Profil' : 'Profil')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Profile Header -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="h-32 bg-gradient-to-r from-blue-400 to-indigo-500"></div>
        <div class="px-8 pb-8">
            <div class="relative flex flex-col sm:flex-row sm:items-end sm:gap-6 -mt-12 mb-6">
                <div class="relative">
                    <img src="{{ $user->profile_photo_url }}" 
                         alt="{{ $user->username }}" 
                         class="w-32 h-32 rounded-3xl object-cover ring-4 ring-white shadow-md bg-white">
                </div>
                
                <div class="flex-1 mt-6 sm:mt-0">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $user->username }}</h1>
                            <p class="text-gray-500 font-medium">{{ $user->email }}</p>
                        </div>

                        <div class="flex items-center gap-3">
                            @auth
                                @if(auth()->id() === $user->id)
                                    <a href="{{ route('posts.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold transition-all shadow-sm hover:shadow-md active:scale-95">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Neuer Beitrag</span>
                                    </a>
                                @else
                                    @php
                                        $isFollowing = auth()->user()->following()->where('users.id', $user->id)->exists();
                                    @endphp
                                    <div class="follow-toggle-container" data-user-id="{{ $user->id }}">
                                        @if($isFollowing)
                                            <form action="{{ route('unfollow', $user) }}" method="POST" class="inline follow-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-gray-100 hover:bg-red-50 hover:text-red-600 text-gray-700 font-bold py-2.5 px-6 rounded-xl transition-all border border-transparent hover:border-red-100">
                                                    Unfollow
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('follow', $user) }}" method="POST" class="inline follow-form">
                                                @csrf
                                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl transition-all shadow-sm hover:shadow-md">
                                                    Follow
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="flex items-center gap-8 py-6 border-t border-gray-50 mt-2">
                <div class="text-center">
                    <div class="text-xl font-bold text-gray-900">{{ $user->posts->count() }}</div>
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Beiträge</div>
                </div>
                <div class="text-center" id="follower-count">
                    <div class="text-xl font-bold text-gray-900"><strong>{{ $user->followers()->count() }}</strong></div>
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Follower</div>
                </div>
                <div class="text-center">
                    <div class="text-xl font-bold text-gray-900">{{ $user->following()->count() }}</div>
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Folge ich</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Settings/Edit Section (Only for owner) -->
        @auth
            @if(auth()->id() === $user->id)
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <h3 class="font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Einstellungen
                        </h3>
                        
                        <form action="{{ route('profile.update', $user) }}" method="POST" class="space-y-4 mb-8">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 px-1">Benutzername</label>
                                <div class="flex gap-2">
                                    <input type="text" name="username" value="{{ old('username', $user->username) }}" 
                                           class="flex-1 bg-gray-50 border-gray-100 rounded-xl py-2 px-4 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none text-sm font-medium">
                                    <button class="bg-gray-900 hover:bg-black text-white px-4 rounded-xl text-sm font-bold transition-all active:scale-95">
                                        OK
                                    </button>
                                </div>
                                @error('username')<p class="text-red-500 text-xs mt-1 px-1">{{ $message }}</p>@enderror
                            </div>
                        </form>

                        <form action="{{ route('profile.photo', $user) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 px-1">Profilbild ändern</label>
                            <div class="space-y-3">
                                <input type="file" name="profile_photo_url" accept="image/*" 
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all">
                                <button class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 py-2.5 rounded-xl text-sm font-bold transition-all">
                                    Foto hochladen
                                </button>
                            </div>
                            @error('profile_photo_url')<p class="text-red-500 text-xs mt-1 px-1">{{ $message }}</p>@enderror
                        </form>
                    </div>
                </div>
            @endif
        @endauth

        <!-- Posts Grid -->
        <div class="{{ auth()->id() === $user->id ? 'lg:col-span-8' : 'lg:col-span-12' }}">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-2 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <h4 class="font-bold text-gray-900">Beiträge</h4>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @forelse($user->posts ?? [] as $post)
                        <a href="{{ route('posts.show', $post) }}" class="group relative aspect-square bg-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all">
                            @if($post->image_url)
                                <img src="{{ asset($post->image_url) }}" alt="Beitrag" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center p-4 text-center text-gray-400 bg-gray-50">
                                    <p class="text-xs font-medium">{{ Str::limit($post->description, 50) }}</p>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white gap-4">
                                <div class="flex items-center gap-1 font-bold">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                    <span>{{ $post->likes_count ?? 0 }}</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full py-12 text-center">
                            <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-gray-400 font-medium italic">Noch keine Beiträge veröffentlicht.</p>
                        </div>
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
                        container.innerHTML = `
                            <form action="/profile/${userId}/follow" method="POST" class="inline follow-form">
                                <input type="hidden" name="_token" value="${token}">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl transition-all shadow-sm hover:shadow-md">Follow</button>
                            </form>
                        `;
                    } else {
                        container.innerHTML = `
                            <form action="/profile/${userId}/follow" method="POST" class="inline follow-form">
                                <input type="hidden" name="_token" value="${token}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="bg-gray-100 hover:bg-red-50 hover:text-red-600 text-gray-700 font-bold py-2.5 px-6 rounded-xl transition-all border border-transparent hover:border-red-100">Unfollow</button>
                            </form>
                        `;
                    }

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
