<!DOCTYPE html>
<html>
<head>
    <title>E-Mail-Verifizierung</title>
</head>
<body>
    <h1>Hallo {{ $user->name }}</h1>
    <p>Vielen Dank für Ihre Registrierung bei uns. Bitte klicken Sie auf den untenstehenden Link, um Ihre E-Mail-Adresse zu verifizieren:</p>
    <a href="{{ $verificationUrl }}">Meine E-Mail verifizieren</a>
</body>
</html>