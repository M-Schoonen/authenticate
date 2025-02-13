<?php
session_start();

// Controleer of de gebruiker ingelogd is
if (!isset($_SESSION['user_id'])) {
    // Als je niet bent ingelogd wordt je teruggestuurd
    header("Location: login.php");
    exit();
}

echo "Welkom, " . $_SESSION['username'] . "<br>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Uploader</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="logout.php">
        <button>Uitloggen</button>
    </a>
</body>
</html>