{{-- resources/views/posts/create.blade.php --}}

@extends('layouts.app')

@section('title', 'Beitrag erstellen - WebmasterGram')

@section('content')
<div class="container mx-auto max-w-2xl mt-16 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-bold mb-6">Einen neuen Beitrag erstellen</h2>

    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label for="description" class="block text-gray-700 font-medium mb-2">Beschreibung</label>
            <textarea id="description" name="description" rows="5"
                      class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                      required>{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="image_url" class="block text-gray-700 font-medium mb-2">Bild</label>
            <input type="file" id="image_url" name="image_url" accept="image/*"
                   class="w-full px-4 py-2 border rounded-lg @error('image_url') border-red-500 @enderror">
            <p class="text-gray-500 text-sm mt-1">Optional. Max. 5 MB.</p>
            @error('image_url')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg">
                Veröffentlichen
            </button>
            <a href="{{ route('timeline') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-lg">
                Abbrechen
            </a>
        </div>
    </form>
</div>
@endsection
