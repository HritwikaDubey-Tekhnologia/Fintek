<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Default Page</title>
</head>
<body>
    <p>No option selected or an error occurred. Redirecting to the default page...</p>

    <!-- You can add additional content or redirection logic if needed -->

    <?php
    // Example redirection after a few seconds
    header("refresh:5;url=index.php");
    ?>
</body>
</html>
