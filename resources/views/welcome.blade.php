<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webstagram</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="text-center bg-white p-8 rounded-lg shadow-lg">
        <!-- Logo -->
        <img src="{{ asset('images/cover.png') }}" alt="Logo" class="mx-auto mb-6 max-w-xs h-auto">

        <!-- Buttons -->
        <div class="space-x-4">
            <a href="{{ route('login') }}">Anmelden</a>
            <a href="{{ route('register') }}">Registrieren</a>
        </div>
    </div>

</body>
</html>
