{{-- resources/views/auth/verify-email.blade.php --}}

@extends('layouts.app')

@section('title', 'Verify Email - WebmasterGram')

@section('content')
<div class="container mx-auto max-w-md mt-16 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-bold text-center mb-6">Verify Your Email</h2>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 text-green-600 text-center">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <p class="text-gray-700 mb-4">
        Thanks for signing up! Before you can access your WebmasterGram, please verify your email address by clicking the link we sent to your email.
    </p>

    <div class="mb-4">
        <strong>Didn't receive the email?</strong>
    </div>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg">
            Resend Verification Email
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf

        <button type="submit" class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg">
            Logout
        </button>
    </form>
</div>
@endsection
