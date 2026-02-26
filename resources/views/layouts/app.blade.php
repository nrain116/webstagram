{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'WebmasterGram')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F8FAFC] min-h-screen flex flex-col antialiased text-gray-900">

    {{-- Navbar --}}
    <nav class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                    <div class="bg-blue-600 p-2 rounded-xl group-hover:scale-110 transition-transform shadow-lg shadow-blue-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                    </div>
                    <span class="text-xl font-extrabold tracking-tight text-gray-900 group-hover:text-blue-600 transition-colors">WebmasterGram</span>
                </a>

                <div class="flex items-center gap-6">
                    @guest
                        <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 hover:text-blue-600 transition-colors">Anmelden</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2.5 px-6 rounded-xl transition-all shadow-sm active:scale-95">
                            Registrieren
                        </a>
                    @else
                        <div class="flex items-center gap-4">
                            <a href="{{ route('profile.show', Auth::user()) }}" class="flex items-center gap-2 group">
                                <div class="w-10 h-10 rounded-xl overflow-hidden ring-2 ring-gray-50 group-hover:ring-blue-100 transition-all">
                                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->username }}" class="w-full h-full object-cover">
                                </div>
                                <span class="hidden sm:block text-sm font-bold text-gray-700 group-hover:text-blue-600 transition-colors">
                                    {{ Auth::user()->username ?? Auth::user()->email }}
                                </span>
                            </a>
                            
                            <div class="h-6 w-px bg-gray-100 mx-2"></div>

                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Abmelden">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    {{-- Main content --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-gray-100 py-10 text-center">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col items-center gap-4">
                <div class="flex items-center gap-2 opacity-50 grayscale hover:grayscale-0 transition-all cursor-default">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                    </svg>
                    <span class="font-bold tracking-tight">WebmasterGram</span>
                </div>
                <p class="text-gray-400 text-sm font-medium">
                    &copy; {{ date('Y') }} WebmasterGram. Alle Rechte vorbehalten.
                </p>
            </div>
        </div>
    </footer>

    @yield('scripts')

</body>
</html>