{{-- resources/views/auth/verify-email.blade.php --}}

@extends('layouts.app')

@section('title', 'E-Mail verifizieren - WebmasterGram')

@section('content')
<div class="container mx-auto max-w-md mt-16 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-bold text-center mb-6">Verifizieren Sie Ihre E-Mail</h2>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 text-green-600 text-center">
            Ein neuer Verifizierungslink wurde an Ihre E-Mail-Adresse gesendet.
        </div>
    @endif

    <p class="text-gray-700 mb-4">
        Vielen Dank für Ihre Anmeldung! Bevor Sie auf Ihr WebmasterGram zugreifen können, verifizieren Sie bitte Ihre E-Mail-Adresse, indem Sie auf den Link klicken, den wir Ihnen gesendet haben.
    </p>

    <div class="mb-4">
        <strong>Keine E-Mail erhalten?</strong>
    </div>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg">
            Verifizierungs-E-Mail erneut senden
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf

        <button type="submit" class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg">
            Abmelden
        </button>
    </form>
</div>
@endsection
