<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Signup Verification Email</h1>
    <p>Hi, {{ $user->name }}</p>
    <p>Please click on this link to verify <a href="{{ $verificationUrl }}">Verify Link</a></p>
</body>

</html>
