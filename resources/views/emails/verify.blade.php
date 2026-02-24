<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body>
    <h1>Hello {{ $user->name }}</h1>
    <p>Thank you for registering with us. Please click the link below to verify your email address:</p>
    <a href="{{ $verificationUrl }}">Verify My Email</a>
</body>
</html>