{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')

@section('title', 'Sign Up - WebmasterGram')

@section('content')
<div class="container mx-auto max-w-md mt-16 p-6 bg-white shadow-lg rounded-lg">
    <img src="{{ asset('images/cover.png') }}" alt="Logo" class="mx-auto mb-6 max-w-xs h-auto">
    <h2 class="text-2xl font-bold text-center mb-6">Account erstellen</h2>

    {{-- Session status --}}
    @if (session('status'))
        <div class="mb-4 text-green-600">
            {{ session('status') }}
        </div>
    @endif

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-4">
            <label for="username" class="block text-gray-700 mb-1">Username</label>
            <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('username') border-red-500 @enderror">
            @error('username')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 mb-1">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 mb-1">Password</label>
            <input id="password" type="password" name="password" required
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-gray-700 mb-1">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg">
            Sign Up
        </button>
    </form>

    <div class="mt-6 text-center text-gray-500">Or continue with</div>

    <div class="mt-4 flex justify-center gap-4">
        <a href="/"
           class="flex items-center gap-2 border px-4 py-2 rounded-lg hover:bg-gray-100">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd"
                      d="M12 2C6.477 2 2 6.477 2 12c0 4.418 2.865 8.166 6.839 9.489.5.092.682-.217.682-.483 0-.237-.009-.868-.014-1.703-2.782.604-3.369-1.342-3.369-1.342-.454-1.153-1.11-1.46-1.11-1.46-.908-.62.069-.608.069-.608 1.003.071 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.091-.646.35-1.087.636-1.338-2.22-.253-4.555-1.111-4.555-4.943 0-1.091.39-1.984 1.029-2.682-.103-.254-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.026a9.564 9.564 0 012.5-.336c.849.004 1.705.114 2.5.336 1.909-1.295 2.748-1.026 2.748-1.026.545 1.377.202 2.393.1 2.647.64.698 1.028 1.591 1.028 2.682 0 3.841-2.337 4.687-4.566 4.934.36.31.682.923.682 1.86 0 1.343-.012 2.427-.012 2.756 0 .268.18.579.688.481C19.138 20.164 22 16.416 22 12c0-5.523-4.477-10-10-10z"
                      clip-rule="evenodd" />
            </svg>
            GitHub
        </a>
    </div>

    <p class="mt-6 text-center text-gray-500 text-sm">
        Already have an account? <a href="{{ route('login') }}" class="text-blue-500">Sign In</a>
    </p>
</div>
@endsection
