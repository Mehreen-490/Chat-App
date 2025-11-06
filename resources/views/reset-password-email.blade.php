<div>
    <!-- If you do not have a consistent goal in life, you can not live it in a consistent way. - Marcus Aurelius -->
</div>
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
    <p>Please click on this link to reset password <a href="{{ $passwordResetUrl }}">Verify Link</a></p>
</body>

</html>
