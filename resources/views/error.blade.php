<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error</title>
</head>
<body>
    <div class="mt-5">
        <h1>Error Occurred</h1>
        <p>
            {{ isset($e) ? e($e) : 'An unexpected error has occurred.' }}
        </p>
    </div>
</body>
</html>

