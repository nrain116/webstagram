{{-- resources/views/auth/forgot-password.blade.php --}}

@extends('layouts.app')

@section('title', 'Forgot Password - WebmasterGram')

@section('content')
<div class="container mx-auto max-w-md mt-16 p-6 bg-white shadow-lg rounded-lg">
    <img src="{{ asset('images/cover.png') }}" alt="Logo" class="mx-auto mb-6 max-w-xs h-auto">
    <h2 class="text-2xl font-bold text-center mb-6">Forgot Your Password?</h2>

    @if (session('status'))
        <div class="mb-4 text-green-600 text-center">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <p class="text-gray-700 mb-4">
        No problem. Just let us know your email address and we will email you a password reset link.
    </p>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-gray-700 mb-1">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg">
            Send Password Reset Link
        </button>
    </form>

    <p class="mt-6 text-center text-gray-500 text-sm">
        Remembered? <a href="{{ route('login') }}" class="text-blue-500">Sign In</a>
    </p>
</div>
@endsection
