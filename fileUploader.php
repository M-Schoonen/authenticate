<?php
session_start();

// Controleer of de gebruiker ingelogd is
if (!isset($_SESSION['user_id'])) {
    // Als je niet bent ingelogd, wordt je teruggestuurd
    header("Location: login.php");
    exit();
}

// Haal het rechten_niveau op uit de sessie
$rechten_niveau = $_SESSION['rechten_niveau'];

// Controleer of de gebruiker genoeg rechten heeft voor uploaden (rechten_niveau >= 2)
if ($rechten_niveau < 2) {
    // Als de gebruiker geen rechten heeft om te uploaden, stuur dan door naar een foutpagina of geef een bericht
    echo "Je hebt geen rechten om bestanden te uploaden.";
    exit();
}

echo "Welkom, " . $_SESSION['username'] . "<br>";
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Uploader</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Bestand Uploaden</h2>
    <form action="fileUploader.php" method="POST" enctype="multipart/form-data">
        <label for="bestand">Selecteer bestand:</label>
        <input type="file" name="bestand" id="bestand" required><br>
        <button type="submit">Uploaden</button>
    </form>

    <br>
    <a href="logout.php">
        <button>Uitloggen</button>
    </a>
</body>
</html>

<?php
// Verwerk de bestand upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['bestand'])) {
    // Zorg ervoor dat het bestand is geüpload zonder fouten
    if ($_FILES['bestand']['error'] == 0) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['bestand']['name']);

        // Controleer of het bestand nog niet bestaat
        if (file_exists($uploadFile)) {
            echo "Bestand bestaat al.";
        } else {
            // Verplaats het bestand naar de gewenste map
            if (move_uploaded_file($_FILES['bestand']['tmp_name'], $uploadFile)) {
                echo "Bestand succesvol geüpload!";
            } else {
                echo "Er is een fout opgetreden bij het uploaden van het bestand.";
            }
        }
    } else {
        echo "Fout bij het uploaden van het bestand.";
    }
}
?>