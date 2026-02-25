{{-- resources/views/auth/reset-password.blade.php --}}

@extends('layouts.app')

@section('title', 'Passwort zurücksetzen - WebmasterGram')

@section('content')
<div class="container mx-auto max-w-md mt-16 p-6 bg-white shadow-lg rounded-lg">
    <img src="{{ asset('images/cover.png') }}" alt="Logo" class="mx-auto mb-6 max-w-xs h-auto">
    <h2 class="text-2xl font-bold text-center mb-6">Setzen Sie Ihr Passwort zurück</h2>

    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-4">
            <label for="email" class="block text-gray-700 mb-1">E-Mail-Adresse</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 mb-1">Passwort</label>
            <input id="password" type="password" name="password" required
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-gray-700 mb-1">Passwort bestätigen</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg">
            Passwort zurücksetzen
        </button>
    </form>
</div>
@endsection
