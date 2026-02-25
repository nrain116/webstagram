{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'WebmasterGram')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    {{-- Navbar --}}
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-xl font-bold text-blue-500">WebmasterGram</a>

            <div class="space-x-4">
                @guest
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-500 font-medium">Anmelden</a>
                    <a href="{{ route('register') }}" class="text-gray-700 hover:text-blue-500 font-medium">Registrieren</a>
                @else
                    <a href="{{ route('profile.show', Auth::user()) }}" class="text-gray-700 hover:text-blue-500 font-medium">
                        {{ Auth::user()->username ?? Auth::user()->email }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-blue-500 font-medium">Abmelden</button>
                    </form>
                @endguest
            </div>
        </div>
    </nav>

    {{-- Main content --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white shadow-inner py-4 mt-8 text-center text-gray-500 text-sm">
        &copy; {{ date('Y') }} WebmasterGram. Alle Rechte vorbehalten.
    </footer>

    @yield('scripts')

</body>
</html>