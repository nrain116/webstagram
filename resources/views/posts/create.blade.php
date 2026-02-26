@extends('layouts.app')

@section('title', 'Beitrag erstellen - WebmasterGram')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8">
            <div class="flex items-center gap-3 mb-8">
                <div class="bg-blue-600 p-2.5 rounded-xl shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Neuer Beitrag</h2>
                    <p class="text-sm text-gray-500 font-medium tracking-tight">Teilen Sie Ihre Gedanken mit der Community</p>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 rounded-2xl border border-red-100">
                    <div class="flex items-center gap-2 text-red-700 font-bold text-sm mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span>Bitte korrigieren Sie die Fehler:</span>
                    </div>
                    <ul class="list-disc pl-5 text-sm text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="description" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 px-1">Beschreibung</label>
                    <textarea id="description" name="description" rows="5"
                              placeholder="Was beschäftigt Sie gerade?"
                              class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-5 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none text-gray-800 leading-relaxed @error('description') border-red-200 bg-red-50/30 @enderror"
                              required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1.5 px-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="image_url" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 px-1">Bild hinzufügen</label>
                    <div class="relative group">
                        <div class="absolute inset-0 bg-blue-50 rounded-2xl border-2 border-dashed border-blue-200 group-hover:border-blue-400 transition-colors pointer-events-none"></div>
                        <input type="file" id="image_url" name="image_url" accept="image/*"
                               class="relative w-full opacity-0 z-10 cursor-pointer h-32 px-4 py-2 @error('image_url') border-red-500 @enderror">
                        <div class="absolute inset-0 flex flex-col items-center justify-center gap-2 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-400 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm font-bold text-blue-600">Bild auswählen oder hierher ziehen</span>
                            <span class="text-xs text-gray-400">Optional. Max. 5 MB.</span>
                        </div>
                    </div>
                    @error('image_url')
                        <p class="text-red-500 text-xs mt-1.5 px-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-50">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-8 rounded-2xl transition-all shadow-sm hover:shadow-md active:scale-[0.98]">
                        Beitrag veröffentlichen
                    </button>
                    <a href="{{ route('timeline') }}" class="flex-1 bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold py-3.5 px-8 rounded-2xl text-center transition-all active:scale-[0.98]">
                        Abbrechen
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
