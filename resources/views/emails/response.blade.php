<!DOCTYPE html>
<html>

<head>
    <title>Response Saved</title>
</head>

<body style="font-family:Arial; text-align:center; padding:50px;">
    @if($status == 'interested')
        <h1 style="color:green;">
            Thank You 🙌
        </h1>
        <p>
            Your response has been recorded as Interested.
        </p>
    @else
        <h1 style="color:red;">
            Unsubscribed Successfully
        </h1>
        <p>
            You will no longer receive emails from us.
        </p>
    @endif
</body>

</html>
